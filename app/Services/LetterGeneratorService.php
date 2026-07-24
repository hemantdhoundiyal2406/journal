<?php

namespace App\Services;

use App\Models\Article;
use App\Models\JournalSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class LetterGeneratorService
{
    /**
     * Generate PDF letter by type
     */
    public function generate(Article $article, string $type)
    {
        $article->load(['authors', 'volume', 'issue']);
        $correspondingAuthor = $article->correspondingAuthor ?? $article->authors->first();
        
        $data = [
            'article' => $article,
            'correspondingAuthor' => $correspondingAuthor,
            'journalName' => JournalSetting::getByKey('journal_name', 'International Journal of Research & Innovation'),
            'issn' => JournalSetting::getByKey('print_issn', 'ISSN: 2345-6789'),
            'e_issn' => JournalSetting::getByKey('online_issn', 'E-ISSN: 2345-6790'),
            'editorInChief' => JournalSetting::getByKey('editor_in_chief', 'Prof. Dr. Alexander Vance'),
            'publisher' => JournalSetting::getByKey('publisher_name', 'Global Academic Publishing Corp.'),
            'todayDate' => date('F d, Y'),
            'type' => $type
        ];

        $viewName = match ($type) {
            'acceptance' => 'letters.acceptance',
            'rejection' => 'letters.rejection',
            'revision' => 'letters.revision',
            'publication' => 'letters.publication',
            'copyright' => 'letters.copyright',
            default => 'letters.acceptance',
        };

        $pdf = Pdf::loadView($viewName, $data)->setPaper('a4', 'portrait');
        return $pdf;
    }
}
