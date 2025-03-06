<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{


    public function index()
    {

        $data = Country::paginate(PAGINATION_COUNT);

        return view('admin.countries.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('country-add')) {

            return view('admin.countries.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $country = new Country();

            $country->name_en = $request->get('name_en');
            $country->name_ar = $request->get('name_ar');
            $country->name_fr = $request->get('name_fr');
            $country->currency = $request->get('currency');
            $country->sympol = $request->get('sympol');


            if ($country->save()) {

                return redirect()->route('countries.index')->with(['success' => 'Country created']);
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
        if (auth()->user()->can('country-edit')) {
            $data = Country::findorFail($id);
            return view('admin.countries.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $country = Country::findorFail($id);
        try {

            $country->name_en = $request->get('name_en');
            $country->name_ar = $request->get('name_ar');
            $country->name_fr = $request->get('name_fr');
            $country->currency = $request->get('currency');
            $country->sympol = $request->get('sympol');

            if ($country->save()) {

                return redirect()->route('countries.index')->with(['success' => 'Country update']);
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
            $country = Country::findOrFail($id);



            // Delete the category
            if ($country->delete()) {
                return redirect()->back()->with(['success' => 'country deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
