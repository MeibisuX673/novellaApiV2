<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    'middleware' => 'auth:api'
], function () {

    Route::apiResource('admins','App\Http\Controllers\AdminController')->names('admin');

    Route::delete('/user_intermediaries/{user_id}/{server_id}',"App\Http\Controllers\UserIntermediariesController@destroy");

    Route::get('/user_intermediaries/showUsersByServerId/{server_id}',"App\Http\Controllers\UserIntermediariesController@showServerId");

    Route::get('/user_intermediaries/showServersByUserId/{user_id}',"App\Http\Controllers\UserIntermediariesController@showUserId");

    Route::get('users/check',"App\Http\Controllers\UsersController@showUserByEmail");

    Route::apiResource('chats','App\Http\Controllers\ChatController')->names('chat');

    Route::apiResource('users','App\Http\Controllers\UsersController')->names('user');

    Route::apiResource('servers','App\Http\Controllers\ServerController')->names('server');

    Route::apiResource('message_contents','App\Http\Controllers\MessageContentController')->names('message_content');

    Route::post('/upload', function(Request $request) {

    $filename =  $request->thing->getClientOriginalName();
    $id = $request->user_id;
    $path = $request->file('thing')->storeAs(
    'avatars', $id, 'public');
    // dd($request->file('thing'));
   
    Storage::disk('google')->put($id, file_get_contents(__DIR__ . '\..\storage\app\public\avatars\\' . $id));
    Storage::disk('public')->delete('avatars\\' . $id);
    return response()->json($request->thing->getClientOriginalName());

    
 });


Route::get('/upload/{user_id}', function($user_id) {

    $files = Storage::disk('google')->files();
    
    foreach ($files as $value) {
        $detail = Storage::disk('google')->getMetadata($value);
        if($detail['filename'] === $user_id){
            $url = Storage::disk('google')->url($value);
            return response()->json(['user_id'=>$user_id,'dowloand'=>$url]);
        }
    }
    
});

Route::put('/upload/{user_id}', function(Request $request,$user_id) {

    $files = Storage::disk('google')->files();
    foreach ($files as $value) {
        $detail = Storage::disk('google')->getMetadata($value);
        if($detail['filename'] === $user_id){
            $url = Storage::disk('google')->url($value);
            Storage::disk('google')->delete($value);
        }
    }

    $filename =  $request->thing->getClientOriginalName();
    $id = $user_id;
    $path = $request->file('thing')->storeAs(
    'avatars', $id, 'public');

    Storage::disk('google')->put($id, file_get_contents(__DIR__ . '\..\storage\app\public\avatars\\' . $id));
    Storage::disk('public')->delete('avatars\\' . $id);
    
});

Route::delete('/upload/{user_id}', function($user_id) {
    $files = Storage::disk('google')->files();
    foreach ($files as $value) {
        $detail = Storage::disk('google')->getMetadata($value);
        if($detail['filename'] === $user_id){
            $url = Storage::disk('google')->url($value);
            Storage::disk('google')->delete($value);
        }
    }

});
});

Route::apiResource('messages_types','App\Http\Controllers\MessageTypeController')->names('messages_type');

Route::group([
    'middleware' => 'api',
    
    'prefix' => 'auth'

], function ($router) {


    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
        
});

