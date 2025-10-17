<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsSampleExport;
use App\Models\Brand;
use App\Models\Crv;
use App\Models\Tax;
use Maatwebsite\Excel\Excel as ExcelWriter;

class ProductController extends Controller
{

    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Toggle status: if 1 make it 2, if 2 make it 1
            $product->status = $product->status == 1 ? 2 : 1;
            $product->save();

            $statusText = $product->status == 1 ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Product {$statusText} successfully",
                'new_status' => $product->status,
                'status_text' => $product->status == 1 ? 'Active' : 'Not Active'
            ]);
        } catch (\Exception $ex) {
            Log::error($ex);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status'
            ], 500);
        }
    }

    public function showImportPage()
    {
        return view('admin.products.import');
    }
    public function storeFromExcel(Request $request)
    {
        try {
            // Check if the request has a file
            if ($request->hasFile('file')) {
                // Import the file using the ProductsImport class
                Excel::import(new ProductsImport, $request->file('file'));
            }

            return redirect()->route('products.index')->with(['success' => 'Products imported successfully']);
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }


    public function downloadSample()
    {
        return Excel::download(new ProductsSampleExport, 'sample_products.xlsx', ExcelWriter::XLSX);
    }

    public function search(Request $request)
    {
        $query = $request->input('term');
        $products = Product::where('name_ar', 'LIKE', "%{$query}%")
            ->orWhere('number', 'LIKE', "%{$query}%")
            ->orWhere('barcode', 'LIKE', "%{$query}%") // Add barcode search condition
            ->with([
                'units' => function ($query) {
                    $query->select('units.id', 'units.name_ar', 'product_units.product_id as pivot_product_id', 'product_units.unit_id as pivot_unit_id', 'product_units.selling_price');
                },
                'unit:id,name_ar'
            ])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name_ar,
                    'unit' => [
                        'id' => $product->unit->id,
                        'name' => $product->unit->name_ar,
                        'selling_price' => $product->unit->selling_price,
                    ],
                    'units' => $product->units->map(function ($unit) {
                        return [
                            'id' => $unit->id,
                            'name' => $unit->name_ar,
                            'selling_price' => $unit->selling_price,
                        ];
                    }),
                    'barcode' => $product->barcode, // Include barcode in the response
                    'tax' => $product->tax,
                    'selling_price' => $product->selling_price_for_user,
                ];
            });

        return response()->json($products);
    }




    public function getPrices($id)
    {
        // Find the product by ID and load its units relationship
        $product = Product::with(['units' => function ($query) use ($id) {
            $query->select('product_units.selling_price', 'product_units.product_id')
                ->where('product_units.product_id', '=', $id);
        }])->find($id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                'selling_price' => null,
                'selling_price_for_user' => null
            ]);
        }

        // Get the selling price from the first unit if available
        $sellingPrice = $product->units->first() ? $product->units->first()->pivot->selling_price : null;

        // Get the selling_price_for_user directly from the product
        $sellingPriceForUser = $product->selling_price_for_user ?? null;

        return response()->json([
            'selling_price' => $sellingPrice,
            'selling_price_for_user' => $sellingPriceForUser
        ]);
    }


    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%$search%")
                    ->orwhere('name_en', 'LIKE', "%$search%")
                    ->orWhere('number', 'LIKE', "%$search%")
                    ->orWhere('barcode', 'LIKE', "%$search%");
            });
        }

        $data = $query->latest()->paginate(PAGINATION_COUNT);

        return view('admin.products.index', compact('data'));
    }

    public function create()
    {
        if (auth()->user()->can('product-add')) {
            $categories = Category::get();
            $units = Unit::get();
            $brands = Brand::get();
            $taxes = Tax::get(); // Add this line
            $crvs = Crv::get(); // Add this line
            $lastNumber = Product::latest('number')->value('number');
            $newNumber = $lastNumber ? $lastNumber + 1 : 1;
            return view('admin.products.create', compact('categories', 'units', 'brands', 'newNumber', 'taxes', 'crvs'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            // Validate product type specific fields
            $rules = [
                'product_type' => 'required|in:1,2,3',
                'number' => 'required',
                'barcode' => 'required|unique:products,barcode',
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'unit' => 'required|exists:units,id',
            ];

            // Add conditional validation based on product type
            $productType = $request->input('product_type');

            if ($productType == '1' || $productType == '3') { // Retail or Both
                $rules['selling_price_for_user'] = 'required|numeric|min:0';
            }


            $request->validate($rules);

            // Create a new product
            $product = new Product();

            $product->product_type = $request->input('product_type');
            $product->number = $request->input('number');
            $product->barcode = $request->input('barcode');
            $product->name_en = $request->input('name_en');
            $product->name_ar = $request->input('name_ar');
            $product->description_en = $request->input('description_en');
            $product->description_ar = $request->input('description_ar');

            // Modified tax handling
            if ($request->input('has_tax') == '1' && $request->input('tax_id')) {
                $tax = Tax::find($request->input('tax_id'));
                $product->tax = $tax ? $tax->value : 0;
            } else {
                $product->tax = 0;
            }

            // Modified crv handling
            if ($request->input('has_crv') == '1' && $request->input('crv_id')) {
                $crv = Crv::find($request->input('crv_id'));
                $product->crv = $crv ? $crv->value : 0;
            } else {
                $product->crv = 0;
            }

            $product->points = $request->input('points');

            // Set values based on product type
            if ($productType == '1' || $productType == '3') { // Retail or Both
                $product->selling_price_for_user = $request->input('selling_price_for_user');
            } else {
                $product->selling_price_for_user = 0;
            }


            $product->rating = $request->input('rating');
            $product->total_rating = $request->input('total_rating');
            $product->in_stock = $request->input('in_stock');
            $product->status = $request->input('status');
            $product->category_id = $request->input('category');
            $product->unit_id = $request->input('unit');
            $product->brand_id = $request->input('brand') ?? null;
            $product->save();

            // Handle product images
            if ($request->hasFile('photo')) {
                $photos = $request->file('photo');
                foreach ($photos as $photo) {
                    $photoPath = uploadImage('assets/admin/uploads', $photo);
                    if ($photoPath) {
                        $productImage = new ProductPhoto();
                        $productImage->photo = $photoPath;
                        $product->productImages()->save($productImage);
                    }
                }
            }

            // Handle product units - only for wholesale or both types
            if (($productType == '2' || $productType == '3') && $request->has('units')) {
                foreach ($request->units as $index => $unit_id) {
                    if (!empty($unit_id)) { // Only create if unit is selected
                        ProductUnit::create([
                            'product_id' => $product->id,
                            'unit_id' => $unit_id,
                            'barcode' => $request->barcodes[$index] ?? null,
                            'releation' => $request->releations[$index] ?? 1,
                            'selling_price' => $request->selling_prices[$index] ?? 0,
                        ]);
                    }
                }
            }

            return redirect()->route('products.index')->with(['success' => 'Product created']);
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }



    public function edit($id)
    {
        if (auth()->user()->can('product-edit')) {
            $data = Product::findOrFail($id); // Retrieve the category by ID
            $categories = Category::get();
            $brands = Brand::get();
            $units = Unit::all();
            $taxes = Tax::get(); // Add this line
            $crvs = Crv::get(); // Add this line
            return view('admin.products.edit', ['units' => $units, 'categories' => $categories, 'brands' => $brands, 'data' => $data, 'taxes' => $taxes, 'crvs' => $crvs]);
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate product type specific fields
            $rules = [
                'product_type' => 'required|in:1,2,3',
                'number' => 'required',
                'barcode' => 'required|unique:products,barcode,' . $id,
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'unit' => 'required|exists:units,id',
            ];

            // Add conditional validation based on product type
            $productType = $request->input('product_type');

            if ($productType == '1' || $productType == '3') { // Retail or Both
                $rules['selling_price_for_user'] = 'required|numeric|min:0';
            }

        

            $request->validate($rules);

            $product = Product::findOrFail($id);

            $product->product_type = $request->input('product_type');
            $product->number = $request->input('number');
            $product->barcode = $request->input('barcode');
            $product->name_en = $request->input('name_en');
            $product->name_ar = $request->input('name_ar');
            $product->description_en = $request->input('description_en');
            $product->description_ar = $request->input('description_ar');

            // Modified tax handling
            if ($request->input('has_tax') == '1' && $request->input('tax_id')) {
                $tax = Tax::find($request->input('tax_id'));
                $product->tax = $tax ? $tax->value : 0;
            } else {
                $product->tax = 0;
            }

            // Modified crv handling
            if ($request->input('has_crv') == '1' && $request->input('crv_id')) {
                $crv = Crv::find($request->input('crv_id'));
                $product->crv = $crv ? $crv->value : 0;
            } else {
                $product->crv = 0;
            }

            $product->points = $request->input('points');

            // Set values based on product type
            if ($productType == '1' || $productType == '3') { // Retail or Both
                $product->selling_price_for_user = $request->input('selling_price_for_user');
            } else {
                $product->selling_price_for_user = 0;
            }

          

            $product->rating = $request->input('rating');
            $product->total_rating = $request->input('total_rating');
            $product->in_stock = $request->input('in_stock');
            $product->status = $request->input('status');
            $product->category_id = $request->input('category');
            $product->unit_id = $request->input('unit');
            $product->brand_id = $request->input('brand') ?? null;

            // Handle product images
            if ($request->hasFile('photo')) {
                // Delete all previous photos associated with the product
                $product->productImages()->delete();

                // Upload and insert new photos
                $photos = $request->file('photo');
                foreach ($photos as $photo) {
                    $photoPath = uploadImage('assets/admin/uploads', $photo);
                    if ($photoPath) {
                        $productImage = new ProductPhoto();
                        $productImage->photo = $photoPath;
                        $product->productImages()->save($productImage);
                    }
                }
            }

            if ($product->save()) {
                // Handle product units - only for wholesale or both types
                ProductUnit::where('product_id', $id)->delete();

                if (($productType == '2' || $productType == '3') && $request->has('units')) {
                    foreach ($request->units as $index => $unit_id) {
                        if (!empty($unit_id)) { // Only create if unit is selected
                            ProductUnit::create([
                                'product_id' => $product->id,
                                'unit_id' => $unit_id,
                                'barcode' => $request->barcodes[$index] ?? null,
                                'releation' => $request->releations[$index] ?? 1,
                                'selling_price' => $request->selling_prices[$index] ?? 0,
                            ]);
                        }
                    }
                }

                return redirect()->route('products.index')->with(['success' => 'Product updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong while updating the product']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $item_row = Product::select("id")->where('id', '=', $id)->first();

            if (!empty($item_row)) {

                $flag = Product::where('id', '=', $id)->delete();

                if ($flag) {
                    return redirect()->back()
                        ->with(['success' => '   Delete Succefully   ']);
                } else {
                    return redirect()->back()
                        ->with(['error' => '   Something Wrong']);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => '   cant reach fo this data   ']);
            }
        } catch (\Exception $ex) {

            return redirect()->back()
                ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
        }
    }
}
