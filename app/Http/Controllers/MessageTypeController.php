<?php

namespace App\Http\Controllers;

use App\Models\MessageType;
use Illuminate\Http\Request;
use Validator;

class MessageTypeController extends Controller
{
    /**
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(MessageType::all(),200);
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
            'type'=>'required|min:3',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $messageType = MessageType::create($request->all());
        return response()->json($messageType,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageType  $messageType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $messageType = MessageType::find($id);
        if(is_null($messageType)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($messageType,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MessageType  $messageType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'type'=>'required|min:3',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $messageType = MessageType::find($id);
        if(is_null($messageType)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        $messageType->update($request->all());
        return response()->json($messageType,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MessageType  $messageType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $messageType = MessageType::find($id);
        if(is_null($messageType)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $messageType->delete();
       
        return response()->json('',204);
    }
}
