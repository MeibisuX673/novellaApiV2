<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


 
class Admins extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
    	
    	'admin_id',
    	
    	'admin_name',
    	'admin_phone',
    	'admin_email',
    ];
    
   
}
