<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'subject_title' => $this->subject_title,
            'subject_description' => $this->subject_description,
            'uploads' => UploadResource::collection($this->uploads)
        ];
    }


}
