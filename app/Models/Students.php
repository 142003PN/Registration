<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Students extends Model
{
    protected $fillable = ['id', 'first_name', 'middle_name', 'last_name', 'program_id'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Programs::class);
    }
}
