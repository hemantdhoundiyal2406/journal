<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleFile extends Model
{
    protected $fillable = [
        'article_id',
        'file_type',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
