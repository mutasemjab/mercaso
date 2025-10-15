<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {

        $data = Setting::paginate(PAGINATION_COUNT);

        return view('admin.settings.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('setting-add')) {
            return view('admin.settings.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {
        if (auth()->user()->can('setting-add')) {
            try {
                $setting = new Setting();
                $setting->min_order = $request->get('min_order');
                $setting->min_order_wholeSale = $request->get('min_order_wholeSale');



                if ($setting->save()) {
                    return redirect()->route('admin.setting.index')->with(['success' => 'setting created']);
                } else {
                    return redirect()->back()->with(['error' => 'Something wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('setting-edit')) {
            $data = Setting::findorFail($id);
            return view('admin.settings.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->can('setting-edit')) {
            $setting = Setting::findorFail($id);
            try {
                $setting->min_order = $request->get('min_order');
                $setting->min_order_wholeSale = $request->get('min_order_wholeSale');

                if ($setting->save()) {
                    return redirect()->route('admin.setting.index')->with(['success' => 'setting update']);
                } else {
                    return redirect()->back()->with(['error' => 'Something wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $setting = Setting::findOrFail($id);
            
            // Toggle status: if 1 make it 2, if 2 make it 1
            $setting->status = $setting->status == 1 ? 2 : 1;
            $setting->save();
            
            $statusText = $setting->status == 1 ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "Setting {$statusText} successfully",
                'new_status' => $setting->status,
                'status_text' => $setting->status == 1 ? 'Active' : 'Inactive'
            ]);
            
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status'
            ], 500);
        }
    }
}
