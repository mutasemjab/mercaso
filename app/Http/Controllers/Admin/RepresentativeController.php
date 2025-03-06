<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{

    public function index()
    {

        $data = Representative::where('shop_id',auth()->user()->shop_id)->paginate(PAGINATION_COUNT);

        return view('admin.representatives.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('representative-add')) {

            return view('admin.representatives.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $representative = new Representative();

            $representative->name = $request->get('name');
            $representative->phone = $request->get('phone');
            $representative->shop_id = auth()->user()->shop_id;



            if ($representative->save()) {

                return redirect()->route('representatives.index')->with(['success' => 'representative created']);
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
        if (auth()->user()->can('representative-edit')) {
            $data = Representative::findorFail($id);
            return view('admin.representatives.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $representative = Representative::findorFail($id);
        try {

            $representative->name = $request->get('name');
            $representative->phone = $request->get('phone');

            if ($representative->save()) {

                return redirect()->route('representatives.index')->with(['success' => 'representative update']);
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
            $representative = Representative::findOrFail($id);

            // Delete the category
            if ($representative->delete()) {
                return redirect()->back()->with(['success' => 'unit deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}
