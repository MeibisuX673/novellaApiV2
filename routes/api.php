<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\MainRoleIntermediaryController;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Http\Controllers\ChatController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([
    'middleware' => 'auth:apiUser'
], function () {

    Route::get('/showChats/{server_id}',[ChatController::class, 'showChats']);

    Route::post('/giveRole',[MainRoleIntermediaryController::class, 'store']);

    Route::apiResource('admins','App\Http\Controllers\AdminController')->names('admin');

    Route::delete('/user_intermediaries/{user_id}/{server_id}',"App\Http\Controllers\UserIntermediariesController@destroy");

    Route::get('/servers/showServers',"App\Http\Controllers\ServerController@showServers");

    Route::get('/servers/showUsers/{server_id}',"App\Http\Controllers\ServerController@showUsers");

    Route::post('/user_intermediaries',"App\Http\Controllers\UserIntermediariesController@store");

    // Route::get('users/check',"App\Http\Controllers\UsersController@checkAuthorize");

    Route::apiResource('chats','App\Http\Controllers\ChatController')->names('chat');

    Route::apiResource('users','App\Http\Controllers\UsersController')->names('user');

    Route::apiResource('servers','App\Http\Controllers\ServerController')->names('server');

    Route::apiResource('message_contents','App\Http\Controllers\MessageContentController')->names('message_content');

    Route::post('/upload', function(Request $request) {

        $user = Auth::user();

        if(is_null($user)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }
        $file = $request->file('avatar');

        if(is_null($file)){
            return response()->json(['error'=>true,'message'=>'Bad request'],400);
        }

        $mimeType = $file->file('avatar')->getClientMimetype();

            if( str_starts_with  ( $mimeType,'image') !== true){
                return response()->json(['error'=>true, 'message' => 'bad request'],400);
            }

        $filename =  $request->avatar->getClientOriginalName();
        $id = $user['user_id'];
        $path = $file->storeAs(
        'avatars', $id, 'public');
        // dd($request->file('avatar'));
        
        Storage::disk('google')->put($id, file_get_contents(__DIR__ . '/../storage/app/public/avatars/' . $id));
        Storage::disk('public')->delete('avatars/' . $id);
        return response()->json(['user_id'=>$id,'name'=>$filename],201);

    
    });


    Route::get('/upload/{user_id}', function($user_id) {

        $user = Users::find($user_id);

            if(is_null($user)){
                return response()->json(['error'=>true,'message'=>'Not Found'],404);
            }

        $files = Storage::disk('google')->files();
        
        foreach ($files as $value) {
            $detail = Storage::disk('google')->getMetadata($value);
            if($detail['filename'] === $user_id){
                $url = Storage::disk('google')->url($value);
                return response()->json(['user_id'=>(int) $user_id,'dowloand'=>$url],200);
            }
        }
    
    });

    Route::put('/upload/{user_id}', function(Request $request,$user_id) {

        $user = Users::find($user_id);

        if(is_null($user)){
            return response()->json(['error'=>true,'message'=>'Not Found'],404);
        }

        $file = $request->file('avatar');

        if(is_null($file)){
            return response()->json(['error'=>true,'message'=>'Bad request'],400);
        }

        $mimeType = $file->getClientMimetype();

        if( str_starts_with  ( $mimeType,'image') !== true){
            return response()->json(['error'=>true, 'message' => 'bad request'],400);
        }

        $files = Storage::disk('google')->files();
        foreach ($files as $value) {
            $detail = Storage::disk('google')->getMetadata($value);
            if($detail['filename'] === $user_id){
                
                Storage::disk('google')->delete($value);
            }
        }

        $filename =  $request->avatar->getClientOriginalName();
        $id = $user_id;
        $path = $request->file('avatar')->storeAs(
        'avatars', $id, 'public');

        Storage::disk('google')->put($id, file_get_contents(__DIR__ . '/../storage/app/public/avatars/' . $id));
        Storage::disk('public')->delete('avatars/' . $id);

        return response()->json(['user_id'=>(int) $user_id,'filename'=>$filename],200);
        
    });

    Route::delete('/upload', function() {

        $user = Auth::user();

            if(is_null($user)){
                return response()->json(['error'=>true,'message'=>'Not Found'],404);
            }

        $files = Storage::disk('google')->files();
        foreach ($files as $value) {
            $detail = Storage::disk('google')->getMetadata($value);
            if($detail['filename'] === $user['user_id']){
                
                Storage::disk('google')->delete($value);
                return response()->json(['message'=>'deleted'],200);
            }
        }

    });
});




// Route::post('/upload', function(Request $request) {

//        dd($request->file());

//         // $filename =  $request->avatar->getClientOriginalName();
//         // $id = $request->user_id;
//         // $path = $request->file('avatar')->storeAs(
//         // 'avatars', $id, 'public');
//         // // dd($request->file('avatar'));
       
//         // Storage::disk('google')->put($id, file_get_contents(__DIR__ . '\..\storage\app\public\avatars\\' . $id));
//         // Storage::disk('public')->delete('avatars\\' . $id);
//         // return response()->json(['user_id'=>$id,'name'=>$filename],201);

    
//  });

Route::apiResource('messages_types','App\Http\Controllers\MessageTypeController')->names('messages_type');

// Route::group([
//     'middleware' => 'auth:apiUser',
    
//     'prefix' => 'auth'

// ], function ($router) {


//     Route::post('/login', [AuthController::class, 'login'])->name('login');
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::get('/user-profile', [AuthController::class, 'userProfile']);
        
// });


Route::group([
    'middleware' => 'api',
    
    'prefix' => 'auth.user'

], function ($router) {


    Route::post('/login', [AuthUserController::class, 'login'])->name('login');
    Route::post('/register', [AuthUserController::class, 'register']);
    Route::post('/logout', [AuthUserController::class, 'logout']);
    Route::post('/refresh', [AuthUserController::class, 'refresh']);
    Route::get('/user-profile', [AuthUserController::class, 'userProfile']);
        
});



