<?php

namespace App\Http\Controllers;

use App\Models\MessageContent;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserIntermediary;
use App\Models\MessageType;
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

        $user = Auth::user();

        $serverId = Chat::select('server_id')->where('chat_id',$request->chat_id)->first();

        if(empty($serverId)){
            return response()->json(['error'=>true,'message'=>'Chat not found']);
        }
        $check = UserIntermediary::select('id')->where('user_id',$user['user_id'])->where('server_id', $serverId['serverId']);

        if(empty($check)){
            return response()->json(['error'=> true, 'message' => 'User not found in server'],404);
        }

        $typeMessage = MessageType::select('id')->where('type', $request->type_message)->first();

        if(empty($check)){
            return response()->json(['error'=> true, 'message' => 'Type not found'],404);
        }


        $rules = [
            'type_message'=>'required',
            'content'=>'required|min:1',
            
            'date'=>'required|date',
            'time'=>'required',
            'chat_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $messageContent = MessageContent::create(['type_id' => $typeMessage['id'],'content' => $request->content, 'status' => true,'date' => $request->date,'time' => $request->time,'chat_id' => $request->chat_id,'user_id' => $user["user_id"]]);

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

        $user = Auth::user();

        $message = MessageContent::find($id);

        if(is_null($message)){
            return response()->json(['error'=>true,'message'=>'Message not Found'],404);
        }

        if($user['user_id'] !== $messageContent['user_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }

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
        $user = Auth::user();
        $messageContent = MessageContent::find($id);
        if(is_null($messageContent)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        if($user['user_id'] !== $messageContent['user_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }

        $messageContent->delete();
       
        return response()->json('Deleted',204);
    }
}
