<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorialMember extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'designation',
        'university',
        'country',
        'biography',
        'orcid',
        'google_scholar',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
