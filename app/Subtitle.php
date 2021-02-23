<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    //

    protected $fillable = ['upload_id','language_id', 'fileLink'];

    public function upload(){
        return $this->belongsTo(Upload::class);
    }
}
