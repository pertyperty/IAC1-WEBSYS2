<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->whenLoaded(
                'user',
                fn () => $this->user->name
            ),

            'sections' => $this->whenLoaded(
                'sections',
                fn () => $this->sections->map(fn ($section) => [
                    'id' => $section->id,
                    'name' => $section->name,
                ])
            ),
        ];
    }
}
