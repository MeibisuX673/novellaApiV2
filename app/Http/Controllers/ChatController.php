<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();
        $server = Server::find($request->server_id);
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Seerver no found'],403);
        }
        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        $chat = Chat::create(['chat_name' => $request->chat_name, 'server_id' => $request->server_id]);
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

    public function showChats($server_id){
        // $chatList = [];
        $user = Auth::user();
        $server = Server::find($server_id);
        if(is_null($user->servers()->where('servers_id',$server_id))){
            return response()->json(['error'=>true,'message'=>'User not found the server'],404);
        }
        $chats = $server->chats;
        // $chats = Chat::select('chat_id')->where('server_id',$server['server_id'])->get();
        // foreach($chats as $value){
        //     $chatList[] = Chat::find($value->chat_id);
        // }
        
        return response()->json(['server_id' =>$server['server_id'], 'chats'=>$chats]);
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
            'chat_name'=>'min:3',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user = Auth::user();


        $chat = Chat::find($id);
        if(is_null($chat)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $server = $chat->server;
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        $chat = Chat::find($id);
        $chat->update($request->all());
        return response()->json($chat);
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

        $user = Auth::user();
        $server = $chat->server;
        
        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }
        $chat = Chat::find($id);
        $chat->delete();
       
        return response()->json('',204);
    }
}
