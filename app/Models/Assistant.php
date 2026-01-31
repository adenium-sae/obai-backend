<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'system_prompt',
        'voice_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
