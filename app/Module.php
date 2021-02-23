<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    protected $fillable = ['subject_id','institution_id','title','description','user_id'];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function uploads(){
        return $this->hasMany(Upload::class);
    }
}
