<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Admins;



class AdminController extends Controller
{


    /**
     *
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        
        return response()->json(Admins::query()->paginate(2),200);
    }

    

    /**
     * 
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $rules = [
            'admin_name'=>'required|min:3',
            'admin_phone'=>'required|min:11|max:11|unique:admins,admin_phone',
            'admin_email'=>'required|unique:admins,admin_email',
            'password'=> 'required|min:8'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        $admin = Admins::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password),
                    'admin_phone' => bcrypt($request->admin_phone),
                    'admin_email'=>bcrypt($request->admin_email),]
                ));
        return response()->json($admin,201);
    }

    /**
     *
     * 
     * 
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    


    public function show($id)
    {
        $admin = Admins::find($id);

        if(is_null($admin)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($admin,200);
    }


    /**
     *
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    
        $rules = [
            'admin_name'=>'min:3',
            'admin_phone'=>'min:11|max:11|unique:admins,admin_phone',
            'admin_email'=>'unique:admins,admin_email',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $admin = Admins::find($id);
        if(is_null($admin)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        $admin->update($request->all());
        return response()->json($admin,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $admin = Admins::find($id);
        if(is_null($admin)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $admin->delete();
       
        return response()->json('',204);
    }
}
