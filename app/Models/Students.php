<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Students extends Model
{
    protected $fillable = ['id', 'first_name', 'middle_name', 'last_name', 'program_id'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Programs::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class, 'student_id');
    }
}
