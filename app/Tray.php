<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tray extends Model
{
    //

    protected $fillable = ['user_id','receiver_user_id','upload_id','message','seen',];

    public function user(){
        return $this->belongsTo(User::class);
    }


}
