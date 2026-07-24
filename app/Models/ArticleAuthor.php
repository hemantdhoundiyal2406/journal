<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleAuthor extends Model
{
    protected $fillable = [
        'article_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'institution',
        'country',
        'orcid',
        'is_corresponding',
        'order',
    ];

    protected $casts = [
        'is_corresponding' => 'boolean',
        'order' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
