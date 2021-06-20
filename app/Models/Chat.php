<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    protected $primaryKey = 'chat_id';

    public $timestamps = true;

    protected $fillable = [
    	'chat_id',
    	'server_id',
    	'chat_name',
    ];
    public function server(){
      return $this->belongsTo('App\Models\server','server_id');
    }
}
