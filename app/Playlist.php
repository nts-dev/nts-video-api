<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    //

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
