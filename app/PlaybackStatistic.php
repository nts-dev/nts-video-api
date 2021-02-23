<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaybackStatistic extends Model
{
    //

    protected $fillable = ['user_id','upload_id','playback_position','total_playback','host_ip','host_device','seen',];



    public function user(){
        return $this->belongsTo(User::class);
    }

}
