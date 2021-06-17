<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Admins;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

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
        return response()->json(['user_id'=>$user['user_id'],'user_name'=>$user['user_name']],200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $userCheck = Users::find($id);
        if(is_null($userCheck)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }

       
        //return response()->json([$user['user_id'],$id]);
        if($user['user_id'] !== $userCheck['user_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        

    	$rules = [
    		
    		'user_name'=>'string|max:25',
    		'user_phone'=>'min:11|max:11|unique:user,user_phone',
    		'user_email'=>'email|min:10|unique:user,user_email',
    		'password'=>'min:8',
    	];

    	$validator = Validator::make($request->all(),$rules);
    	if($validator->fails()){
    		return response()->json($validator->errors(),400);
    	}

        $admins = Admins::select('id')->where('admin_id', $user['user_id'])->get();

        if(!empty($admins)){
            foreach ($admins as $value) {
                $admin = Admins::find($value->id);
                $admin->update($request->all());
            }
        }
        //return response()->json($request->user_email);
    	$userCheck->update($request->all());
        return response()->json($userCheck,200);

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
