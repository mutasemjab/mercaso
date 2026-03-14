<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\Notification;
use App\Models\User;
use App\Models\WholeSale;
use App\Models\WholesaleRequest;
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

    public function change_user_type()
    {
        $user = auth()->user();

        if ($user->user_type == 1) {
            // Switching to wholesale — must have an approved verification
            $approvedRequest = WholesaleRequest::where('user_id', $user->id)
                ->where('status', 2)
                ->first();

            if (!$approvedRequest) {
                return response(['errors' => ['You must have an approved wholesale verification to switch to wholesale']], 403);
            }

            $user->user_type = 2;
        } else {
            $user->user_type = 1;
        }

        $user->save();
        return response(['success' => 'The account change successfully'], 200);
    }

    public function verifyWholesale(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'commercial_registration' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        // Check if user already has any wholesale request (pending, approved, or rejected)
        $existingRequest = WholesaleRequest::where('user_id', $user->id)->first();

        if ($existingRequest) {
            return response(['errors' => ['You already have a wholesale request']], 400);
        }

        $imagePath = uploadImage('assets/admin/uploads/commercial_registrations', $request->commercial_registration);

        WholesaleRequest::create([
            'user_id' => $user->id,
            'commercial_registration' => $imagePath,
            'status' => 1,
        ]);

        return response(['success' => 'Your wholesale request has been submitted and is pending approval'], 200);
    }

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

        $wholesaleRequest = WholesaleRequest::where('user_id', $user->id)->latest()->first();

        return response()->json([
            'user' => $user,
            'wholesale_request' => $wholesaleRequest ? [
                'id' => $wholesaleRequest->id,
                'status' => $wholesaleRequest->status == 1 ? 'pending' : ($wholesaleRequest->status == 2 ? 'approved' : 'rejected'),
                'created_at' => $wholesaleRequest->created_at,
            ] : null,
        ]);
   }

}

