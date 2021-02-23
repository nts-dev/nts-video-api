<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstitutionResource extends JsonResource
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
            'name' => $this->name,
            'contact' => $this->contact,
            'address' => $this->address,
            'website' => $this->website,
            'country' => $this->country,
            'state' => $this->state,
            'physical_address' => $this->physical_address,
            'street' => $this->street,
            'users' => $this->profiles
        ];
    }
}
