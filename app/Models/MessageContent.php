<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageContent extends Model
{
    protected $table = 'message_contents';
    public $timestamps = true;

    protected $fillable = [
    	'id',
    	'type_id',
    	'content',
    	'status',
    	'date',
    	'time',
    	'chat_id',
    	'user_id',

    ];
    public function user(){
      return $this->belongsTo('App\Models\Users','user_id');
    }
}
