<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courses extends Model
{
    protected $fillable = ['code', 'title', 'program_id'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Programs::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class, 'course_id');
    }
}
