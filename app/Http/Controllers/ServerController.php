<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Admins;
use App\Models\UserIntermediary;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServerController extends Controller
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
       return response()->json(Server::query()->paginate(2),200);
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
            'server_name'=>'required|min:3|string',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $user = Auth::user();

        Admins::create(['admin_id' => $user['user_id'],'admin_name' => $user['user_name'], 'admin_phone' => $user['user_phone'],'admin_email'=> $user['user_email']]);

        $server = Server::create(['server_name'=>$request->server_name, 'server_description' => $request->server_description, 'admin_id' => $user['user_id']] );

        UserIntermediary::create(['user_id'=>$user['user_id'], 'server_id'=>$server['server_id']]);

        return response()->json($server,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageType  $messageType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $server = Server::find($id);
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($server,200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $rules = [
           'server_name'=>'min:3|string',
            'server_description'=>'string',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $user = Auth::user();
        $server = Server::find($id);
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        
        $server->update($request->all());
        return response()->json($server,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $server = Server::find($id);

        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $user = Auth::user();
        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        $server->delete();
       
        return response()->json('',204);
    }
}
