<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable implements JWTSubject
{

  use HasFactory, Notifiable;

	protected $table = 'user';
  protected $primaryKey = 'user_id';

  public $timestamps = true;


  protected $fillable = [
      'user_id',
      'user_name',
      'user_phone',
      'password',
      'email_verified_at',
      'user_email',
      'created_at',
      'updated_at',
  ];

  protected $hidden = [
        'password',
        'remember_token',
    ];


  
   protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function servers(){
      return $this->hasMany('App\Models\UserIntermediary','user_id');
    }
}
