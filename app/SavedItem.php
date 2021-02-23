<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedItem extends Model
{
    //

    protected $fillable = ['user_id', 'upload_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function upload(){
        return $this->belongsTo(Upload::class);
    }
}
