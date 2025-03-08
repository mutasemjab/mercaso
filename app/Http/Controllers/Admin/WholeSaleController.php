<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;

use App\Models\Shop;
use App\Models\WholeSale;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersSampleExport;
use Maatwebsite\Excel\Excel as ExcelWriter;

class WholeSaleController extends Controller
{

    public function showImportPage()
    {
        return view('admin.wholeSales.import');
    }
    public function storeFromExcel(Request $request)
    {
        try {
            // Check if the request has a file
            if ($request->hasFile('file')) {
                // Import the file using the ProductsImport class
                Excel::import(new UsersImport, $request->file('file'));
            }

            return redirect()->route('admin.wholeSale.index')->with(['success' => 'WholeSale imported successfully']);
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }


    public function downloadSample()
    {
        return Excel::download(new UsersSampleExport, 'sample_woleSales.xlsx', ExcelWriter::XLSX);
    }

    public function index(Request $request)
    {
        // Get the logged-in admin's shop_id
        $admin = auth()->user();
        $shop = Shop::where('id', $admin->shop_id)->first();


        // Check if there's a search query
        if ($request->search) {
            $data = User::where('user_type', 2)
                        ->where(function ($q) use ($request) {
                            $q->where(\DB::raw('CONCAT_WS(" ", `name`, `email`, `phone`)'), 'like', '%' . $request->search . '%');
                        })
                        ->paginate(PAGINATION_COUNT);
        } else {
            $data = User::where('user_type', 2)
                        ->paginate(PAGINATION_COUNT);
        }

        $searchQuery = $request->search;

        return view('admin.wholeSales.index', compact('data', 'searchQuery'));
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->search), 'users.xlsx');
    }


    public function show($id)
    {
        $data = User::where('user_type', 2)->findOrFail($id);
        return view('admin.wholeSales.show', compact('data'));
    }


    public function edit($id)
    {
        if (auth()->user()->can('wholeSale-edit')) {
            $data = User::where('user_type', 2)->findorFail($id);
            return view('admin.wholeSales.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $customer = User::where('user_type', 2)->findorFail($id);
        try {

            $customer->name = $request->get('name');
            if ($request->password) {
                $customer->password = Hash::make($request->password);
            }
            $customer->email = $request->get('email');
            $customer->phone = $request->get('phone');
            $customer->can_pay_with_receivable = $request->get('can_pay_with_receivable');


            if ($request->activate) {
                $customer->activate = $request->get('activate');
            }
            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $customer->photo = $the_file_path;
            }

            if ($customer->save()) {
                return redirect()->route('admin.wholeSale.index')->with(['success' => 'WholeSale update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }
}
