<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    //

    protected $fillable = ['title','description','thumbnailLink','videoLink','module_id','user_id','publish','subject_id', 'upload_folder_index', 'raw_link', 'disk'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }

    public function subtitle(){
        return $this->hasMany(Subtitle::class);
    }

    public function views(){
        return $this->hasMany(View::class);
    }

    public function playlist(){
        return $this->belongsToMany(Playlist::class);
    }

    public function subject(){
        return $this->belongsToMany(Subject::class);
    }

    public function savedItem(){
        return $this->hasMany(SavedItem::class);
    }
}
