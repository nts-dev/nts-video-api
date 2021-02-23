<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    //

    protected $fillable = ['title', 'message', 'user_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
