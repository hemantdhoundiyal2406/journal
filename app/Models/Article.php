<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    protected $fillable = [
        'manuscript_id',
        'title',
        'running_title',
        'category',
        'article_type',
        'abstract',
        'keywords',
        'status',
        'volume_id',
        'issue_id',
        'doi',
        'start_page',
        'end_page',
        'published_at',
        'view_count',
        'download_count',
        'admin_notes',
        'certificate_token',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'download_count' => 'integer',
    ];

    public function authors(): HasMany
    {
        return $this->hasMany(ArticleAuthor::class)->orderBy('order');
    }

    public function correspondingAuthor(): HasOne
    {
        return $this->hasOne(ArticleAuthor::class)->where('is_corresponding', true);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ArticleFile::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(ArticleTimeline::class)->orderBy('created_at', 'desc');
    }

    public function volume(): BelongsTo
    {
        return $this->belongsTo(Volume::class);
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function getManuscriptFileAttribute()
    {
        return $this->files()->where('file_type', 'manuscript')->first();
    }

    public function getSupplementaryFilesAttribute()
    {
        return $this->files()->where('file_type', 'supplementary')->get();
    }

    public function getFormattedAuthorsAttribute(): string
    {
        return $this->authors->map(fn($a) => $a->full_name)->implode(', ');
    }
}
