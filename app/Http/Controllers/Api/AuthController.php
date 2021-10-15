<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register','login']);
        $this->middleware('verified-api')->only('checkVerified');
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:customers',
            'password' => 'required|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        
        $customer = Customer::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);
        
        $token = JWTAuth::fromUser($customer); // get JWT AUTH
        
        event(new Registered($customer));

        if($customer){
            return response()->json([
                'success' => true,
                'user' => $customer,
                'token' => $token
            ]);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Customer::where('email', $request->email)->first();
       
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json($validator->errors(),400);
        }
        
        $token = $user->createToken('ApiToken')->plainTextToken;
    
        $response = [
            'success'   => true,
            'user'      => $user,
            'token'     => $token
        ];
    
        return response($response, 201);

        
    }
    public function logout()
    {
        Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();

        return response()->json([
            'success'    => true
        ], 200);
    }
    public function getUser()
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->user()
        ], 200);
    }


    public function checkToken()
    {
        return response()->json(['success' => true],200);
    }
    
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['token' => $user->createToken($user->name)->plainTextToken]);
    }

    public function checkVerified()
    {
            // $user = Auth::user();
            // if(!$user->user()->hasVerifiedEmail()){

            // }
    }
    
    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }

    // /**
    //  * Get the token array structure.
    //  *
    //  * @param  string $token
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }
}
