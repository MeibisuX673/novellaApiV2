<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
	protected $table = 'user';
  protected $primaryKey = 'user_id';

  public $timestamps = true;


  protected $fillable = [
      'user_id',
      'user_name',
      'user_phone',
      'email_verified_at',
      'user_email',
      'created_at',
      'updated_at',
  ];

  protected $hidden = [
        'password',
        'remember_token',
    ];
}
