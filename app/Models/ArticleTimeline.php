<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleTimeline extends Model
{
    protected $fillable = [
        'article_id',
        'status_from',
        'status_to',
        'comment',
        'created_by',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
