<?php

namespace App\Http\Resources;

use App\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedItemsResource extends JsonResource
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
            'upload_id' => $this->upload_id
        ];
    }
}
