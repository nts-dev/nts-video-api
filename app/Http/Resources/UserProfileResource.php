<?php

namespace App\Http\Resources;

use App\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'profile' => $this->profile,
            'uploads' => UploadResource::collection($this->uploads),
            'trays' => $this->trays,
            'playbackStatistics' => $this->playbackStatistics,
            'savedItems' => $this->savedItems,
            'issues' => $this->issues,
        ];
    }
}


