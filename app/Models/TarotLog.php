<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarotLog extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'card_name',
        'card_image',
        'message',
        'position',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
