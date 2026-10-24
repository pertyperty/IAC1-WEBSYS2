<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'grade',
        'instructions_path',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}