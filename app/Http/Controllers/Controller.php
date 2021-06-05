<?php

/**
 * @OA\Swagger(
 *   schemes={"http"},
 *   host="localhost:8000/api",
 *   basePath="/v1",
 *   
 *   
 *@OA\Info(
 * 	title="laravel swagger api", 
 *  version="1.0",
 *  title = "Novella",
 *  @OA\Contact(
 *  	email = "adminexample.com"
 *  ),
 * ),
 * @OA\Server(
 * 		description = "laravel API swagger",
 * 		url = "http://127.0.0.1:8000/api/v1",
 * 		
 * )
 *
 *@OA\SecurityScheme(
 *	securityScheme="bearerAuth",
 *	type="http",
 *	scheme="bearer",
 *	bearerFormat="JWT"
 *)
 * 
 *
 * 
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
