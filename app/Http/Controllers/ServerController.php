<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Validator;

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
            'admin_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $server = Server::create($request->all());
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
            'admin_id'=>'integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $server = Server::find($id);
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
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
        $server->delete();
       
        return response()->json('',204);
    }
}
