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
use Maatwebsite\Excel\Excel as ExcelWriter;

class ProductController extends Controller
{

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
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%$search%")
                  ->orWhere('number', 'LIKE', "%$search%")
                  ->orWhere('barcode', 'LIKE', "%$search%");
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        return view('admin.products.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->can('product-add')) {
            $categories = Category::get();
            $units = Unit::get();
            $brands = Brand::get();
            $lastNumber = Product::latest('number')->value('number');
            $newNumber = $lastNumber ? $lastNumber + 1 : 1;
            return view('admin.products.create', compact('categories', 'units','brands','newNumber'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Create a new product without saving it to the database yet
            $product = new Product();

            $product->number = $request->input('number');
            $product->barcode = $request->input('barcode');
            $product->name_en = $request->input('name_en');
            $product->name_ar = $request->input('name_ar');
            $product->description_en = $request->input('description_en');
            $product->description_ar = $request->input('description_ar');
            $product->has_variation = $request->input('has_variation');
            $product->tax = $request->input('tax');
            $product->points = $request->input('points');
            $product->selling_price_for_user = $request->input('selling_price_for_user');
            $product->min_order_for_user = $request->input('min_order_for_user');
            $product->min_order_for_wholesale = $request->input('min_order_for_wholesale');
            $product->rating = $request->input('rating');
            $product->total_rating = $request->input('total_rating');
            $product->in_stock = $request->input('in_stock');
            $product->status = $request->input('status');
            $product->category_id = $request->input('category');
            $product->unit_id = $request->input('unit');
            $product->brand_id = $request->input('brand') ?? null;


            if ($product->has_variation) {
                $product->save(); // Save the product first to generate an ID
                $variations = $request->input('variations');
                $quantities = $request->input('available_quantities');
                $attributes = $request->input('attributes');

                foreach ($variations as $key => $variation) {
                    $product->variations()->create([
                        'variation' => $variation,
                        'available_quantity' => $quantities[$key],
                        'attributes' => $attributes[$key],
                    ]);
                }

                if ($request->hasFile('photo')) {
                    $photos = $request->file('photo');
                    foreach ($photos as $photo) {
                        $photoPath = uploadImage('assets/admin/uploads', $photo); // Use the uploadImage function
                        if ($photoPath) {
                            // Create a record in the product_images table for each image using the relationship
                            $productImage = new ProductPhoto();
                            $productImage->photo = $photoPath;

                            $product->productImages()->save($productImage); // Associate the image with the product
                        }
                    }
                }

                if ($request->has('units')) {
                    foreach ($request->units as $index => $unit_id) {
                        ProductUnit::create([
                            'product_id' => $product->id,
                            'unit_id' => $unit_id,
                            'barcode' => $request->barcodes[$index],
                            'releation' => $request->releations[$index],
                            'selling_price' => $request->selling_prices[$index],
                        ]);
                    }
                }

            } else {
                $product->save(); // Save the product without variations

                if ($request->hasFile('photo')) {
                    $photos = $request->file('photo');
                    foreach ($photos as $photo) {
                        $photoPath = uploadImage('assets/admin/uploads', $photo); // Use the uploadImage function
                        if ($photoPath) {
                            // Create a record in the product_images table for each image using the relationship
                            $productImage = new ProductPhoto();
                            $productImage->photo = $photoPath;

                            $product->productImages()->save($productImage); // Associate the image with the product
                        }
                    }
                }

                if ($request->has('units')) {
                    foreach ($request->units as $index => $unit_id) {
                        ProductUnit::create([
                            'product_id' => $product->id,
                            'unit_id' => $unit_id,
                            'barcode' => $request->barcodes[$index],
                            'releation' => $request->releations[$index],
                            'selling_price' => $request->selling_prices[$index],
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
            return view('admin.products.edit', ['units' => $units, 'categories' => $categories, 'brands' => $brands,'data' => $data]);
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->number = $request->input('number');
            $product->barcode = $request->input('barcode');
            $product->name_en = $request->input('name_en');
            $product->name_ar = $request->input('name_ar');
            $product->name_fr = $request->input('name_fr');
            $product->description_en = $request->input('description_en');
            $product->description_ar = $request->input('description_ar');
            $product->description_fr = $request->input('description_fr');
            $product->has_variation = $request->input('has_variation');
            $product->tax = $request->input('tax');
            $product->points = $request->input('points');
            $product->selling_price_for_user = $request->input('selling_price_for_user');
            $product->min_order_for_user = $request->input('min_order_for_user');
            $product->min_order_for_wholesale = $request->input('min_order_for_wholesale');
            $product->rating = $request->input('rating');
            $product->total_rating = $request->input('total_rating');
            $product->in_stock = $request->input('in_stock');
            $product->status = $request->input('status');
            $product->category_id = $request->input('category');
            $product->unit_id = $request->input('unit');
            $product->brand_id = $request->input('brand') ?? null;

           if ($request->hasFile('photo')) {
                // Delete all previous photos associated with the product
                $product->productImages()->delete();

                // Upload and insert new photos
                $photos = $request->file('photo');
                foreach ($photos as $photo) {
                    $photoPath = uploadImage('assets/admin/uploads', $photo); // Use the uploadImage function
                    if ($photoPath) {
                        // Create a record in the product_images table for each image using the relationship
                        $productImage = new ProductPhoto();
                        $productImage->photo = $photoPath;

                        $product->productImages()->save($productImage); // Associate the image with the product
                    }
                }
            }

            if ($product->has_variation) {
                // Handle variations update here
                $variations = $request->input('variations');
                $quantities = $request->input('available_quantities');
                $attributes = $request->input('attributes');

                $product->variations()->delete(); // Delete existing variations for the product

                foreach ($variations as $key => $variation) {
                    $product->variations()->create([
                        'product_id' => $product->id,
                        'variation' => $variation,
                        'available_quantity' => $quantities[$key],
                        'attributes' => $attributes[$key],
                    ]);
                }

            } else {
                // If has_variation is false, delete existing variations
                $product->variations()->delete();
            }



            if ($product->save()) {
                // Handle product units
                ProductUnit::where('product_id', $id)->delete();
                if ($request->has('units')) {
                    foreach ($request->units as $index => $unit_id) {
                        ProductUnit::create([
                            'product_id' => $product->id,
                            'unit_id' => $unit_id,
                            'barcode' => $request->barcodes[$index],
                            'releation' => $request->releations[$index],
                            'selling_price' => $request->selling_prices[$index],
                        ]);
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
