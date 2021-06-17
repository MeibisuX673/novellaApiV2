<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainRoleIntermediary extends Model
{
     protected $fillable = [
        
        'id',
        'role_id',
        'user_id',
        'server_id', 
    ];}
