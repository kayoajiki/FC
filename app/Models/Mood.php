<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mood extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'mood_rating',
        'mood_emoji',
        'memo',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'mood_rating' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
