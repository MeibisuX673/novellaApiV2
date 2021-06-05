<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table = 'servers';
    public $timestamps = true;

    protected $primaryKey = 'server_id';

    protected $fillable = [
    	'server_id',
    	'server_name',
    	'server_description',
    	'admin_id',

    ];
}
