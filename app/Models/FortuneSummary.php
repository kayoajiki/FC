<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FortuneSummary extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'birth_date',
        'birth_time',
        'birth_place',
        'four_pillars_result',
        'numerology_result',
        'ziwei_result',
        'tarot_result',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'calculated_at' => 'date',
            'four_pillars_result' => 'array',
            'numerology_result' => 'array',
            'ziwei_result' => 'array',
            'tarot_result' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
