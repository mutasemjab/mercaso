<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
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
        $resource = 'coupon'; // or auto-detect from class name
        
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
        $data= Coupon::paginate(PAGINATION_COUNT);

        return view('admin.coupons.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupons.create');
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
            $coupon = new Coupon();

            $coupon->code = $request->get('code');
            $coupon->amount = $request->get('amount');
            $coupon->minimum_total = $request->get('minimum_total');
            $coupon->expired_at = $request->get('expired_at');
            if($coupon->save()){
                return redirect()->route('coupons.index')->with(['success' => 'coupon created']);

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
        $data = Coupon::findOrFail($id);
        return view('admin.coupons.edit', ['data' => $data]);
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
            $coupon = Coupon::findOrFail($id);

            $coupon->code = $request->get('code');
            $coupon->amount = $request->get('amount');
            $coupon->minimum_total = $request->get('minimum_total');
            $coupon->expired_at = $request->get('expired_at');

            if ($coupon->save()) {
                return redirect()->route('coupons.index')->with(['success' => 'Coupon updated']);
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

            $item_row = Coupon::select("code")->where('id','=',$id)->first();

            if (!empty($item_row)) {

        $flag = Coupon::where('id','=',$id)->delete();

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
