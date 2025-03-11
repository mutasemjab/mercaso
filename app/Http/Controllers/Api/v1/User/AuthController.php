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
            'password' => 'required|string|min:6',
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



    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validate the request data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update user fields if provided
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the user and return response
        if ($user->save()) {
            return response()->json([
                'message' => ['Your profile has been updated successfully'],
                'user' => $user
            ]);
        } else {
            return response()->json([
                'errors' => ['There was an error updating your profile']
            ], 422);
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

   public function userProfile()
   {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
        ]);
   }

}

