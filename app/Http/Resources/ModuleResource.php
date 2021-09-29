<?php

namespace App\Http\Resources;

use App\Subject;
use App\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'user_id' => $this->user_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            // 'subject' => Collect(Subject::find($this->subject)),
            // 'uploads' => UploadResource::collection($this->uploads)

        ];
    }

}
