<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserIntermediary;
use App\Models\Server;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

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
        $user = Auth::user();

        $server = Server::find($request->server_id);

        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        } 

        $checkUser = Users::find($request->user_id);

        if(!empty($check)){
            return response()->json(['error'=>true,'message'=>'User not found']);
        } 

        $check = UserIntermediary::select('id')->where('server_id',2)->where('user_id', $request->user_id)->first();
        //return response()->json($check);
     //return response()->json(empty($check));

        if(!empty($check)){
            return response()->json(['message'=>'Resourse exists']);
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

    public function showUserId(){
        $servers = [];
        $user = Auth::user();
        if(is_null($user)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);}
        $userIntermediary = DB::table('user_intermediaries')->select('server_id')->where('user_id',$user['user_id'])->get();

         foreach ($userIntermediary as $value) {
            $servers[] = Server::find($value->server_id); 
        }
        
        
        return response()->json(['user_id' =>$user['user_id'], 'servers'=>$servers]);
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
        //     
        $user = Auth::user();
        $server = Server::find($server_id);

        if(empty($server)){
             return response()->json(['error'=>true,'message'=>'Not found server'],404);
        }

        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }  

        if($user['user_id'] === (int)$user_id){
            return response()->json(['error'=>true,'message'=>'Not delete admin'],403);
        }


        $userIntermediary = DB::table('user_intermediaries')->select('id')->where('user_id',$user_id)->where('server_id',$server['server_id'])->first();

        if(empty($userIntermediary)){
            return response()->json(['error'=>true,'message'=>'User not found in server'],404);
        }

        DB::table('user_intermediaries')->where([['user_id', $user_id],['server_id',$server_id]])->delete();
       
        return response()->json('User delete from server',204);
    }
}
