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
    public function server(){
      return $this->belongsTo('App\Models\Server','server_id');
    }
    public function user(){
      return $this->belongsTo('App\Models\Users','user_id');
    }
}
