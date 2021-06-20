<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Validator;
use App\Models\MainRoleIntermediary;
use App\Models\MainRole;


class AuthUserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:apiUser', ['except' => ['login', 'register']]);
    }

    /**
     *
     * 
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function login(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

       
        if (!$token = auth('apiUser')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|between:2,100',
            'user_email' => 'required|string|email|max:100|unique:user',
            'user_phone'=>'required',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $role = MainRole::select('id')->where('main_role_name',"User")->first();
        
        $user = Users::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password), 'user_email'=>$request->user_email,'user_phone'=> $request->user_phone]
                ));
        return response()->json($user);
        MainRoleIntermediary::create(['role_id' => $role['id'] ,'user_id'=> $user->user_id] );

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('apiUser')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth('apiUser')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){

        return response()->json([            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('apiUser')->factory()->getTTL() * 60,
            'user' => auth('apiUser')->user()
        ]);
    }

}