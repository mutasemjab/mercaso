<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class CityController extends Controller
{

    public function index()
    {

        $data = City::paginate(PAGINATION_COUNT);

        return view('admin.cities.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('city-add')) {
            $countries= Country::get();
            return view('admin.cities.create',compact('countries'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $city = new City();

            $city->name_en = $request->get('name_en');
            $city->name_ar = $request->get('name_ar');
            $city->name_fr = $request->get('name_fr');
            $city->country_id = $request->get('country');


            if ($city->save()) {

                return redirect()->route('cities.index')->with(['success' => 'city created']);
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
        if (auth()->user()->can('city-edit')) {
            $data = City::findorFail($id);
            $countries= Country::get();
            return view('admin.cities.edit', compact('data','countries'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $city = City::findorFail($id);
        try {

            $city->name_en = $request->get('name_en');
            $city->name_ar = $request->get('name_ar');
            $city->name_fr = $request->get('name_fr');
            $city->country_id = $request->get('country');

            if ($city->save()) {

                return redirect()->route('cities.index')->with(['success' => 'City update']);
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
            $city = City::findOrFail($id);



            // Delete the category
            if ($city->delete()) {
                return redirect()->back()->with(['success' => 'city deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
