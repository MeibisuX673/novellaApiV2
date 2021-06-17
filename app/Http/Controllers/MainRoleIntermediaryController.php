<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainRole;
use App\Models\MainRoleIntermediary;

use Illuminate\Support\Facades\Auth;
use App\Models\Server;
use App\Models\UserIntermediary;

class MainRoleIntermediaryController extends Controller
{

    public function store(Request $request){


        $user = Auth::user();

        $server = Server::find($request->server_id);

        $role = MainRole::select('id')->where('main_role_name',$request->main_role_name)->first();

        $giveUser = UserIntermediary::select('id')->where('user_id', $request->user_id)->where('server_id', $request->server_id)->first();

        $checkRole = MainRoleIntermediary::select('id')->where('server_id', $request->server_id)->where('user_id', $request->user_id)->where('role_id', $role['id']);

        $checkNull = MainRoleIntermediary::find($checkRole['id']);


        if(!empty($checkRole)){
            return response()->json(['message'=>'Resourse exists']);
        }


       // return response()->json(empty($role));

        if(empty($giveUser)){
            return response()->json(['error' => true, 'message'=>'User not found'],404);
        }

        if(empty($role)){
            return response()->json(['error' => true, 'message'=>'Role not found'],404);
        }

        if(empty($server)){
            return response()->json(['error' => true, 'message'=>'Server not found'],404);
        }

        if($user['user_id'] !== $server['admin_id']){
            return response()->json(['error'=>true,'message'=>'Forbidden'],403);
        }  

        if($checkNull['server_id'] !== null){
            MainRoleIntermediary::create(['role_id' => $role['id'], 'user_id'=>$request->user_id,'server_id' => $request->server_id]);
        }

        MainRoleIntermediary::update(['role_id' => $role['id'], 'user_id'=>$request->user_id,'server_id' => $request->server_id]);

        return response()->json(['role_id' => $role['id'], 'user_id'=>$request->user_id,'server_id' => $request->server_id]);

    }
}
