<?php

namespace App\Http\Resources;

use App\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'subject_title' => $this->subject_title,
            'subject_description' => $this->subject_description,
            'total_uploads' => $this->uploads->count(),
            'uploads' => UploadResource::collection($this->uploads)->take(10),
        ];
    }

}

