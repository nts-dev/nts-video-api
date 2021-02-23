<?php

namespace App\Http\Resources;

use App\Upload;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'first_name' => isset($this->profile)? $this->profile->first_name : null,
            'last_name' => isset($this->profile)? $this->profile->last_name : null,
            'display_name' => $this->display_name,
            'profile_pic_url' => isset($this->profile)? $this->profile->profile_pic_url : null,
            'uploads' => UploadResource::collection($this->uploads)
        ];
    }
}
