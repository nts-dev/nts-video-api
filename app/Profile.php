<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = ['user_id','first_name','last_name', 'address','phone', 'country', 'institution_id', 'profile_pic_url', 'firebase_id', ];

    public function institution(){
        return $this->belongsTo(Institution::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
