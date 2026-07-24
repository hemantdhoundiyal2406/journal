<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'expertise',
        'university',
        'country',
        'notes',
    ];
}
