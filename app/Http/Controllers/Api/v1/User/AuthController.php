<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\Notification;
use App\Models\User;
use App\Models\WholeSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function get_business_type()
    {
        $data = BusinessType::get();
        return response()->json(['data'=>$data]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'business_type' => 'required|exists:business_types,id',
            'phone' => 'nullable|unique:users,phone',
            'user_type' => 'required|integer',
            // Add other necessary validation rules for wholesale fields
        ], [
            'name.required' => 'The name field is required.',
            'password.required' => 'The password field is required.',
            'business_type.required' => 'The business type field is required.',
            'phone.unique' => 'The phone has already been taken for the selected user type.',
            'email.unique' => 'The email has already been taken for the selected user type.',
            'user_type.required' => 'The user type field is required.',
            'user_type.integer' => 'The user type must be an integer.',
            // Add other necessary validation messages for wholesale fields
        ]);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');
            $user->user_type = $request->get('user_type');
            $user->email = $request->get('email');
            $user->business_type_id = $request->get('business_type');
            $user->password = Hash::make($request->get('password'));

            if ($request->has('fcm_token')) {
                $user->fcm_token = $request->get('fcm_token');
            }

            $user->save();
            DB::commit();

            $accessToken = $user->createToken('authToken')->accessToken;
            return response(['user' => $user, 'token' => $accessToken], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => 'Registration failed, please try again.'], 500);
        }
    }



    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|integer',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'user_type.required' => 'The user type field is required.',
            'user_type.integer' => 'The user type must be an integer.',
        ]);


        $user = User::where('email', $request->email)
                        ->where('user_type', $request->user_type)
                        ->first();

        if (!$user) {
            return response(["message" => "User not found."], 404);
        }

        if ($user->activate == 2) {
            return response(['errors' => ['Your account has been deactivated']], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        return response(['user' => $user, 'token' => $accessToken], 200);
    }



   public function updateProfile(Request $request){


       $user =  auth()->user();

       if(isset($request->password)){
           $user->password = Hash::make($request->password);
       }

       if ($request->has('photo')) {
        $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
        $user->photo = $the_file_path;
     }

       if($user->save()){
           return response(['message'=>['Your setting has been changed'],'user'=>$user]);
       }else{
           return response(['errors'=>['There is something wrong']],402);
       }
   }

   public function mobileVerified(Request $request)
   {

       $user = auth()->user();
       if(!$user){
       return response(['errors' => ['Unauthenticated']], 402);

       }

       $user->is_verified = 1;

       if ($user->save()) {
           return response(['message' => ['Your setting has been changed'], 'user' => $user], 200);
       } else {
           return response(['errors' => ['There is something wrong']], 402);
       }
   }

    public function countries()
    {
        $countries = Country::get();

        return response()->json(['data'=>$countries]);
    }

    public function get_cities($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        $cities = $country->cities;

        return response()->json(['data' => $cities]);
    }

   public function deleteAccount(Request $request)
   {
      $user =  auth()->user();

      if (isset($request->activate)) {
          $user->activate = 2;
      }

      if ($user->save()) {
          return response(['message' => ['Your Account deleted successfully']]);
      } else {
          return response(['errors' => ['There is something wrong']], 402);
      }
   }


      public function notifications()
   {
       $user = auth()->user();
       $notifications = Notification::orderBy('id','DESC')->get();
           return response([ 'data' => $notifications], 200);

   }

    public function upgrade(Request  $request)
    {
        $user = User::find(auth()->id());
        $user->user_type  = 2;
        $user->activate = 0;
        $user->save();

        $details = new WholeSale();
        $details->user_id = $user->id;
        $details->tax_number = $request->get('tax_number');
        $details->company_type = $request->get('company_type');

        if ($request->other_company_type) {
            $details->other_company_type = $request->get('other_company_type');
        }

        if ($request->import_license) {
            $import_license = $request->import_license->store('wholesales/import_license', 'public');
            $details->import_license = $import_license;
        }
        if ($request->store_license) {
            $store_license = $request->store_license->store('wholesales/store_license', 'public');
            $details->store_license = $store_license;
        }

        if ($request->commercial_record) {
            $commercial_record = $request->commercial_record->store('wholesales/commercial_record', 'public');
            $details->commercial_record = $commercial_record;
        }
        $details->save();
        return response(['message' => ['Your setting has been changed'], 'user' => $user]);
    }

}

