<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Users;
use Validator;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return response()->json(Users::query()->paginate(2),200);
    }

    public function checkAuthorize(Request $request){
        $user = DB::table('user')->where('user_email',$request->user_email)->first();

        if(is_null($user)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        if(bcrypt($request->password) !== $user->password){
            return response()->json(['error'=>true,'message'=>'Not Found','authorized' => false],404);
        }

        return response()->json(['user_id'=>(int) $user->user_id,'authorized' => true]);
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$rules = [
    		'user_name'=>'required|string|max:25',
    		'user_phone'=>'required|min:11|max:11|unique:user,user_phone',
    		'user_email'=>'email|required|min:10|unique:user,user_email',
    		'password'=>'required|min:8',
            'remember_token' =>'boolean',

    	];

    	$validator = Validator::make($request->all(),$rules);
    	if($validator->fails()){
    		return response()->json($validator->errors(),400);
    	}

        $user = Users::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password),
                    'user_email'=> bcrypt($request->user_email),'user_phone'=> bcrypt($request->user_phone)]
                ));
        return response()->json($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$user = Users::find($id);
    	if(is_null($user)){
    		return response()->json(['error'=>true,'message'=>'Not Found'],404);
    	}
        return response()->json($user,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        
  
    	$rules = [
    		
    		'user_name'=>'string|max:25',
    		'user_phone'=>'min:11|max:11|unique:users,user_phone',
    		'user_email'=>'email|min:10|unique:users,user_email',
    		'password'=>'min:8',
    	];

    	$validator = Validator::make($request->all(),$rules);
    	if($validator->fails()){
    		return response()->json($validator->errors(),400);
    	}

    	$user = Users::find($id);
    	if(is_null($user)){
        	return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
    	
    	$user->update($request->all());
        return response()->json($user,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Users::find($id);
        if(is_null($user)){
        	return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $user->delete();
       
        return response()->json('',200);
    }
}
