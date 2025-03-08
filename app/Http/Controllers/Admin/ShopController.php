<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Shop;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{


    public function index()
    {

        $data = Shop::paginate(PAGINATION_COUNT);

        return view('admin.shops.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('shop-add')) {
            return view('admin.shops.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $shop = new Shop();

            $shop->name = $request->get('name');
            $shop->name_of_manager = $request->get('name_of_manager');
            $shop->email = $request->get('email');
            $shop->password = Hash::make($request->password);
            $shop->phone = $request->get('phone');
            $shop->photo = $request->get('photo');
            $shop->address = $request->get('address');
            $shop->activate = $request->get('activate');
            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $shop->photo = $the_file_path;
            }

            if ($shop->save()) {
                $admin = new Admin([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->name,
                    'password' => Hash::make($request->password),
                    'shop_id' => auth()->user()->id,
                ]);
                $admin->save();
                return redirect()->route('shops.index')->with(['success' => 'Shop created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('shop-edit')) {
            $data = Shop::findorFail($id);

            return view('admin.shops.edit', compact('data',));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $shop = Shop::findorFail($id);
        try {

            $shop->name = $request->get('name');
            $shop->name_of_manager = $request->get('name_of_manager');
            $shop->email = $request->get('email');
            if ($request->password) {
                $shop->password = Hash::make($request->password);
            }
            $shop->phone = $request->get('phone');
            $shop->photo = $request->get('photo');
            $shop->address = $request->get('address');
            if ($request->activate) {
                $shop->activate = $request->get('activate');
            }

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $shop->photo = $the_file_path;
            }
            if ($shop->save()) {
                // Optionally update the Admin model if necessary
                $adminData = [];

                if ($request->password) {
                    $adminData['password'] = Hash::make($request->password);
                }

                if ($request->name) {
                    $adminData['username'] = $request->name;
                }

                if (!empty($adminData)) {
                    Admin::updateOrCreate(['shop_id' => $shop->id], $adminData);
                }


                return redirect()->route('shops.index')->with(['success' => 'Shop update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $shop = Shop::findOrFail($id);
            // Delete the category
            if ($shop->delete()) {
                return redirect()->back()->with(['success' => 'shop deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
