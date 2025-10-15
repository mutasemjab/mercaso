<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DeliveryAvailability;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
      public function __construct()
    {
        // Define permission mappings for each method
        $this->middleware('auth:admin');
        
        $this->middleware(function ($request, $next) {
            $this->checkPermissions();
            return $next($request);
        });
    }

    private function checkPermissions()
    {
        $action = request()->route()->getActionMethod();
        $resource = 'delivery'; // or auto-detect from class name
        
        $permissions = [
            'index'   => $resource . '-table',
            'show'    => $resource . '-table', 
            'create'  => $resource . '-add',
            'store'   => $resource . '-add',
            'edit'    => $resource . '-edit',
            'update'  => $resource . '-edit',
            'destroy' => $resource . '-delete',
        ];

        if (isset($permissions[$action])) {
            if (!auth()->user()->can($permissions[$action])) {
                abort(403, __('messages.access_denied'));
            }
        }
    }

    public function index()
    {
        $data = Delivery::with('availabilities')->paginate(PAGINATION_COUNT);
        return view('admin.deliveries.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        return view('admin.deliveries.create', compact('daysOfWeek'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'place' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'zip_code' => 'nullable|numeric',
            'availabilities' => 'array',
            'availabilities.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.time_from' => 'required|date_format:H:i',
            'availabilities.*.time_to' => 'required|date_format:H:i|after:availabilities.*.time_from',
        ]);

        try {
            DB::beginTransaction();
            
            $delivery = new Delivery();
            $delivery->place = $request->get('place');
            $delivery->price = $request->get('price');
            $delivery->zip_code = $request->get('zip_code');
            
            if ($delivery->save()) {
                // Save availabilities if provided
                if ($request->has('availabilities')) {
                    foreach ($request->availabilities as $availability) {
                        if (!empty($availability['day_of_week']) && 
                            !empty($availability['time_from']) && 
                            !empty($availability['time_to'])) {
                            
                            DeliveryAvailability::create([
                                'delivery_id' => $delivery->id,
                                'day_of_week' => $availability['day_of_week'],
                                'time_from' => $availability['time_from'],
                                'time_to' => $availability['time_to'],
                                'is_active' => isset($availability['is_active']) ? true : false
                            ]);
                        }
                    }
                }
                
                DB::commit();
                return redirect()->route('deliveries.index')->with(['success' => 'Delivery created successfully']);
            } else {
                DB::rollback();
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = Delivery::with('availabilities')->findOrFail($id);
        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        return view('admin.deliveries.edit', compact('data', 'daysOfWeek'));
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
        $request->validate([
            'place' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'zip_code' => 'nullable|numeric',
            'availabilities' => 'array',
            'availabilities.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.time_from' => 'required|date_format:H:i',
            'availabilities.*.time_to' => 'required|date_format:H:i|after:availabilities.*.time_from',
        ]);

        try {
            DB::beginTransaction();
            
            $delivery = Delivery::findOrFail($id);
            $delivery->place = $request->get('place');
            $delivery->price = $request->get('price');
            $delivery->zip_code = $request->get('zip_code');
            if ($delivery->save()) {
                // Delete existing availabilities
                $delivery->availabilities()->delete();
                
                // Save new availabilities if provided
                if ($request->has('availabilities')) {
                    foreach ($request->availabilities as $availability) {
                        if (!empty($availability['day_of_week']) && 
                            !empty($availability['time_from']) && 
                            !empty($availability['time_to'])) {
                            
                            DeliveryAvailability::create([
                                'delivery_id' => $delivery->id,
                                'day_of_week' => $availability['day_of_week'],
                                'time_from' => $availability['time_from'],
                                'time_to' => $availability['time_to'],
                                'is_active' => isset($availability['is_active']) ? true : false
                            ]);
                        }
                    }
                }
                
                DB::commit();
                return redirect()->route('deliveries.index')->with(['success' => 'Delivery updated successfully']);
            } else {
                DB::rollback();
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
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
            $delivery = Delivery::findOrFail($id);
            
            if ($delivery->delete()) {
                return redirect()->back()->with(['success' => 'Delivery deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

    public function manageAvailabilities($id)
    {
        $delivery = Delivery::with('availabilities')->findOrFail($id);
        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        return view('admin.deliveries.availabilities', compact('delivery', 'daysOfWeek'));
    }


}
