<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'institution',
        'country',
        'orcid',
        'total_articles_count',
        'published_articles_count',
    ];

    protected $casts = [
        'total_articles_count' => 'integer',
        'published_articles_count' => 'integer',
    ];
}
