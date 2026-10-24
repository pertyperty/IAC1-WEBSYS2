<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Section extends Model
{
    protected $fillable = ['name'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'section_student'
        );
    }

    public function assignments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Assignment::class,
            SectionStudent::class,
            'section_id',
            'student_id',
            'id',
            'student_id'
        );
    }
}