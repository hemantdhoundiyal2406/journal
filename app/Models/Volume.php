<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Volume extends Model
{
    protected $fillable = [
        'volume_number',
        'year',
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
