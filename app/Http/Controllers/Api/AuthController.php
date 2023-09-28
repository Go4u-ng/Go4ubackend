<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Cuisine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
   
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'restaurant_name' => 'required',
                'restaurant_phone' => 'required',

            ]);

            if ($email = User::where('email', $request->email)->first()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email already exists',
                ]);
            }

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            
            //create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 1,
                'password' => Hash::make($request->password)
            ]);

            //create restaurant
            $restaurant = Restaurant::create([
                'owners_name' => $request->restaurant_owners_name,
                'name' => $request->restaurant_name,
                'phone' => $request->restaurant_phone,
                'address' => $request->restaurant_address,
                'city' => $request->restaurant_city,
                'state' => $request->restaurant_state,
                'user_id' => $user->id,
            ]);

            $user->restaurant_id = $restaurant->id;
            $user->save();

            //create cuisines which comes in array
            if($request->restaurant_cuisine){
                foreach($request->restaurant_cuisine as $cuisine){
                    $cuisine = Cuisine::create([
                        'name' => $cuisine,
                        'restaurant_id' => $restaurant->id,
                    ]);
                }
            }


            return response()->json([
                'status' => true,
                'message' => 'Account Created Successfully'
                // 'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
