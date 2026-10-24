<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'title' => $this->title,
            'grade' => $this->grade,

            'instructions_url' => $this->instructions_path
                ? Storage::disk('public')->url(
                    $this->instructions_path
                )
                : null,
        ];
    }
}
