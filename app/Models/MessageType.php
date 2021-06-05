<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageType extends Model
{
    protected $table = 'message_types';
    public $timestamps = true;

    protected $fillable = [
    	'id',
    	'type',

    ];
}
