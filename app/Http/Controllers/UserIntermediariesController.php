<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserIntermediary;
use App\Models\Server;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class UserIntermediariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(UserIntermediary::query()->paginate(2),200);
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
            'user_id'=>'required|integer',
            'server_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $userIntermediary = UserIntermediary::create($request->all());
        return response()->json($userIntermediary,201);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userIntermediary = UserIntermediary::find($id);
        if(is_null($userIntermediary)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        return response()->json($userIntermediary,200);
    }

    public function showUserId($id){
        $servers = [];
        $user = Users::find($id);
        if(is_null($user)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);}
        $userIntermediary = DB::table('user_intermediaries')->select('server_id')->where('user_id',"$id")->get();

         foreach ($userIntermediary as $value) {
            $servers[] = Server::find($value->server_id); 
        }
        
        
        return response()->json(['user_id' =>$id, 'servers'=>$servers]);
    }

    public function showServerId($id){
        $users = [];
        $server = Server::find($id);
        if(is_null($server)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);}
        $userIntermediary = DB::table('user_intermediaries')->select('user_id')->where('server_id',"$id")->get();

        foreach ($userIntermediary as $value) {
            $users[] = Users::find($value->user_id);
            // $servers[] = DB::table('servers')->select()->whereJsonContains('user_id', )
        }
    return response()->json(['server_id' =>$id, 'users'=>$users]);  
    // return response()->json(['user_id' => $userIntermediary]);

    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
           'user_id'=>'required|integer',
            'server_id'=>'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $userIntermediary = UserIntermediary::find($id);
        if(is_null($userIntermediary)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        
        $userIntermediary->update($request->all());
        return response()->json($userIntermediary,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id,$server_id)
    {
        

        // $server = Server::find($id);
        // if(is_null($server)){
        //     return response()->json(['error'=>true,'message'=>'Not Found'],404);}
         $userIntermediary = DB::table('user_intermediaries')->select('id')->where('user_id',"$user_id")->where('server_id',"$server_id")->first();

        DB::table('user_intermediaries')->where([['user_id', $user_id],['server_id',$server_id]])->delete();
       
        return response()->json('',204);
    }
}
