<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programs extends Model
{
    protected $fillable = ['name'];

    public function students(): HasMany
    {
        return $this->hasMany(Students::class, 'program_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Courses::class, 'program_id');
    }
}
