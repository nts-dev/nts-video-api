<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadTimelineInformation extends Model
{
    //
    protected $fillable = ['upload_id', 'content'];

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
