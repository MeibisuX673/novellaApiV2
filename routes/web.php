<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/upload',function(){
//     return view('login');
// });

// Route::post('/upload', function(Request $request) {

//       $file = $request->file('thing');
//        // $f = $request->file('thing')->getClientMimetype();
//        dump($file);
//        // if( str_starts_with  ( $f,'image') !== true){
//        // 		return response()->json(['message' => 'bad request', 'error'=>],400);
//        // 	}
       	
//        // dump($request->file('thing'));
       

//         // $filename =  $request->thing->getClientOriginalName();
//         // $id = $request->user_id;
//         // $path = $request->file('thing')->storeAs(
//         // 'avatars', $id, 'public');
//         // // dd($request->file('thing'));
       
//         // Storage::disk('google')->put($id, file_get_contents(__DIR__ . '\..\storage\app\public\avatars\\' . $id));
//         // Storage::disk('public')->delete('avatars\\' . $id);
//         // return response()->json(['user_id'=>$id,'name'=>$filename],201);

    
//  });
