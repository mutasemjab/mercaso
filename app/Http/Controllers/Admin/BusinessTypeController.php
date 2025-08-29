<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BusinessTypeController extends Controller
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
        $resource = 'businessType'; // or auto-detect from class name
        
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
        $data= BusinessType::paginate(PAGINATION_COUNT);

        return view('admin.businessTypes.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.businessTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $businessType = new BusinessType();

            $businessType->name = $request->get('name');



            if($businessType->save()){
                return redirect()->route('businessTypes.index')->with(['success' => 'business Type created']);

            }else{
                return redirect()->back()->with(['error' => 'Something wrong']);
            }

        }catch(\Exception $ex){
           Log::error($ex);
           return redirect()->back()
            ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
            ->withInput();
        }

    }


    public function edit($id)
    {
        $data = BusinessType::findOrFail($id);
        return view('admin.businessTypes.edit', ['data' => $data]);
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
            $businessType = BusinessType::findOrFail($id);

            $businessType->name = $request->get('name');

            if ($businessType->save()) {
                return redirect()->route('businessTypes.index')->with(['success' => 'business Type updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            // Log the exception for debugging purposes
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

            $item_row = BusinessType::select("place")->where('id','=',$id)->first();

            if (!empty($item_row)) {

        $flag = BusinessType::where('id','=',$id)->delete();

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
