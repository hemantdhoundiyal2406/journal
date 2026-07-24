<?php

namespace App\Services;

use App\Models\Article;
use App\Models\JournalSetting;

class ManuscriptIdGenerator
{
    /**
     * Generate unique sequential manuscript ID format: IJ-2026-0001
     */
    public static function generate(): string
    {
        $year = date('Y');
        $prefix = JournalSetting::getByKey('manuscript_id_prefix', 'IJ');
        
        $latestArticle = Article::where('manuscript_id', 'LIKE', "{$prefix}-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latestArticle) {
            $parts = explode('-', $latestArticle->manuscript_id);
            $lastNum = (int) end($parts);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return "{$prefix}-{$year}-{$nextNum}";
    }
}
