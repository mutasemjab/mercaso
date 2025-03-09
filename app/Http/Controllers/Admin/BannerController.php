<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index()
    {

        $data = Banner::paginate(PAGINATION_COUNT);

        return view('admin.banners.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('banner-add')) {
            $categories= Category::get();
            return view('admin.banners.create',compact('categories'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $banner = new Banner();

            $banner->with_each_other = $request->get('with_each_other');
            $banner->category_id = $request->get('category');

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $banner->photo = $the_file_path;
            }

            if ($banner->save()) {
                return redirect()->route('banners.index')->with(['success' => 'banner created']);
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
        if (auth()->user()->can('category-edit')) {
            $data = Banner::findorFail($id);
            $categories= Category::get();
            return view('admin.banners.edit', compact('data','categories'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findorFail($id);
        try {

            $banner->with_each_other = $request->get('with_each_other');
            $banner->category_id = $request->get('category');

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $banner->photo = $the_file_path;
            }

            if ($banner->save()) {
                return redirect()->route('banners.index')->with(['success' => 'banner update']);
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
             $banner = Banner::findOrFail($id);

            // Delete the category
            if ($banner->delete()) {
                return redirect()->back()->with(['success' => 'banner deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}
