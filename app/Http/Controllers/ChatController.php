<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Validator;

class ChatController extends Controller
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
        return response()->json(Chat::query()->paginate(2),200);    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'server_id'=>'required|integer',
            'chat_name'=>'required|min:3',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $chat = Chat::create($request->all());
        return response()->json($chat,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::find($id);
        if(is_null($chat)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($chat,200);
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'server_id'=>'integer',
            'chat_name'=>'min:3',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $chat = Chat::find($id);
        if(is_null($chat)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        $chat->update($request->all());
        return response()->json($chat,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chat = Chat::find($id);
        if(is_null($chat)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $chat->delete();
       
        return response()->json('',204);
    }
}
