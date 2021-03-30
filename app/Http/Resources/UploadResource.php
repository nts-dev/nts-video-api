<?php

namespace App\Http\Resources;

use App\Subject;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $ROOT = "http://" . $_SERVER['HTTP_HOST'];
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnailLink' => $ROOT . Storage::url($this->disk . "/thumbnails/pic" . rand(0, 3) . ".png"),
            'sprintLink' => $ROOT . Storage::url($this->disk . "/sprint/index.jpg"),
            'videoLink_raw' => $ROOT . Storage::url($this->raw_link),
            'disk' => $this->disk,
            'asInDisk' => $this->raw_link,
            'videoLink' => $ROOT . Storage::url($this->disk . "/hsl/master.m3u8"),
            'publish' => $this->publish,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'user_first_name' => isset($this->user->profile) ? $this->user->profile->first_name : null,
            'user_last_name' => isset($this->user->profile) ? $this->user->profile->last_name : null,
            'user_picture' => isset($this->user->profile) ? $this->user->profile->profile_pic_url : null,
            'total_views' => isset($this->views) ? $this->views->count() : 0,
            'subtitles' => $this->subtitle,
            'subject_id' => $this->subject_id,
            'user_id' => $this->user_id,
            'module_id' => $this->module_id,

        ];
    }


}
