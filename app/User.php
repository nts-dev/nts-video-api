<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','display_name','firebase_id','pic_url','password', 'is_offline'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function playlist(){
        return $this->hasOne(Playlist::class);
    }

    public function modules(){
        return $this->hasMany(Upload::class);
    }

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function trays(){
        return $this->hasMany(Tray::class);
    }

    public function playbackStatistics(){
        return $this->hasMany(PlaybackStatistic::class);
    }

    public function issues(){
        return $this->hasMany(Issue::class);
    }

    public function savedItems(){
        return $this->hasMany(SavedItem::class);
    }

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
}
