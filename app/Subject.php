<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    protected $fillable = ['subject_title','subject_description',];

    public function modules(){
        return $this->hasMany(Module::class);
    }
    public function uploads(){
        return $this->hasMany(Upload::class);
    }
}
