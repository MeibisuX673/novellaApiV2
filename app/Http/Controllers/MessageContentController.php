<?php

namespace App\Http\Controllers;

use App\Models\MessageContent;
use Illuminate\Http\Request;
use Validator;


class MessageContentController extends Controller
{
    /**
     * *@OA\Get(
     *     path = "/message_contents",
     *     operationId = "message_contentsAll",
     *     tags= {"Message_contents"},
     *     summary = "Display a listing of the resource.",
     *      @OA\Response(
     *         response = "200",
     *         description = "Everything is fine",
     *     ),
     *)
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(MessageContent::all(),200);
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
            'type_id'=>'required|integer',
            'content'=>'required|min:1',
            'status'=>'required|min:1|max:1|boolean',
            'date'=>'required|date',
            'time'=>'required',
            'chat_id'=>'required|integer',
            'user_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $messageContent = MessageContent::create($request->all());
        return response()->json($messageContent,201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageContent  $messageContent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messageContent = MessageContent::find($id);
        if(is_null($messageContent)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($messageContent,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MessageContent  $messageContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'type_id'=>'required|integer',
            'content'=>'required|min:1',
            'status'=>'required|min:1|max:1|boolean',
            'date'=>'required|date',
            'time'=>'required',
            'chat_id'=>'required|integer',
            'user_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $messageContent = MessageContent::find($id);
        if(is_null($messageContent)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        $messageContent->update($request->all());
        return response()->json($messageContent,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MessageContent  $messageContent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $messageContent = MessageContent::find($id);
        if(is_null($messageContent)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $messageContent->delete();
       
        return response()->json('',204);
    }
}
