<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $fillable = [
        'volume_id',
        'issue_number',
        'title',
        'publication_month',
        'publication_year',
        'cover_image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'publication_year' => 'integer',
    ];

    public function volume(): BelongsTo
    {
        return $this->belongsTo(Volume::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
