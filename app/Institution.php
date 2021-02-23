<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    //

    protected $fillable = [
        'name','contact','address','website','country','state','physical_address','street',
    ];

    public function profiles(){
        return $this->hasMany(Profile::class);
    }

    public function modules(){
        return $this->hasMany(Module::class);
    }
}
