<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    //

    protected $fillable = ['user_id', 'upload_id'];

    public function upload(){
        return $this->belongsTo(Upload::class);
    }
}
