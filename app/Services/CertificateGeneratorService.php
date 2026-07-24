<?php

namespace App\Services;

use App\Models\Article;
use App\Models\JournalSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateGeneratorService
{
    /**
     * Generate Certificate for Article Author
     */
    public function generate(Article $article, $author = null, string $type = 'publication')
    {
        if (!$article->certificate_token) {
            $article->certificate_token = 'CERT-' . strtoupper(Str::random(12));
            $article->save();
        }

        $article->load(['authors', 'volume', 'issue']);
        $targetAuthor = $author ?? $article->correspondingAuthor ?? $article->authors->first();
        
        $verificationUrl = route('certificate.verify', ['token' => $article->certificate_token]);

        $data = [
            'article' => $article,
            'author' => $targetAuthor,
            'journalName' => JournalSetting::getByKey('journal_name', 'International Journal of Research & Innovation'),
            'issn' => JournalSetting::getByKey('print_issn', 'ISSN: 2345-6789'),
            'e_issn' => JournalSetting::getByKey('online_issn', 'E-ISSN: 2345-6790'),
            'editorInChief' => JournalSetting::getByKey('editor_in_chief', 'Prof. Dr. Alexander Vance'),
            'verificationUrl' => $verificationUrl,
            'certificateToken' => $article->certificate_token,
            'issueTitle' => $article->issue ? "Vol. {$article->volume?->volume_number}, Issue {$article->issue?->issue_number} ({$article->issue?->publication_year})" : "Published Issue",
            'date' => $article->published_at ? $article->published_at->format('F d, Y') : date('F d, Y'),
            'type' => $type,
        ];

        $pdf = Pdf::loadView('letters.certificate', $data)->setPaper('a4', 'landscape');
        return $pdf;
    }
}
