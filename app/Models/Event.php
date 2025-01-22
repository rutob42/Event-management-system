<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Corrected casing
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Corrected casing
use Illuminate\Database\Eloquent\Relations\HasMany; // Corrected casing
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}