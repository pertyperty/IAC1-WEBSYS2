<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(
            Section::class,
            'section_student'
        );
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
