<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssociationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sigle' => $this->sigle,
            'std_min' => "40.000 FCFA",
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
