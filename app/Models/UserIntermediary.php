<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIntermediary extends Model
{
    protected $table = 'user_intermediaries';
   
    protected $primaryKey = 'id';

    

    public $timestamps = true;

    protected $fillable = [
    	
    	'user_id',
    	'server_id',
    	'created_at',
    	'updated_at',
    ];
}
