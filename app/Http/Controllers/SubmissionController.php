<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleFile;
use App\Models\ArticleTimeline;
use App\Models\Author;
use App\Services\ManuscriptIdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function showForm()
    {
        return view('frontend.submit_manuscript');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'running_title' => 'nullable|string|max:200',
            'category' => 'required|string|max:100',
            'article_type' => 'required|string|max:100',
            'abstract' => 'required|string|min:50|max:5000',
            'keywords' => 'required|string|max:500',
            
            // Primary Author & Co-Authors
            'authors' => 'required|array|min:1',
            'authors.*.first_name' => 'required|string|max:100',
            'authors.*.last_name' => 'required|string|max:100',
            'authors.*.email' => 'required|email|max:150',
            'authors.*.mobile' => 'nullable|string|max:30',
            'authors.*.institution' => 'required|string|max:250',
            'authors.*.country' => 'required|string|max:100',
            'authors.*.orcid' => 'nullable|string|max:50',
            'corresponding_author_index' => 'required|integer',

            // Files
            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:20480', // 20MB
            'supplementary_files.*' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,png|max:20480',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'author_notes' => 'nullable|string|max:2000',
        ]);

        DB::beginTransaction();
        try {
            $manuscriptId = ManuscriptIdGenerator::generate();

            $article = Article::create([
                'manuscript_id' => $manuscriptId,
                'title' => $validated['title'],
                'running_title' => $validated['running_title'] ?? null,
                'category' => $validated['category'],
                'article_type' => $validated['article_type'],
                'abstract' => $validated['abstract'],
                'keywords' => $validated['keywords'],
                'status' => 'Submitted',
                'admin_notes' => $validated['author_notes'] ? "Submission Note from Author: " . $validated['author_notes'] : null,
            ]);

            // Save Authors
            $corrIndex = (int) $validated['corresponding_author_index'];
            foreach ($validated['authors'] as $index => $authorData) {
                $isCorresponding = ($index === $corrIndex);

                ArticleAuthor::create([
                    'article_id' => $article->id,
                    'first_name' => $authorData['first_name'],
                    'last_name' => $authorData['last_name'],
                    'email' => $authorData['email'],
                    'mobile' => $authorData['mobile'] ?? null,
                    'institution' => $authorData['institution'],
                    'country' => $authorData['country'],
                    'orcid' => $authorData['orcid'] ?? null,
                    'is_corresponding' => $isCorresponding,
                    'order' => $index + 1,
                ]);

                // Sync into global Author Directory
                $globalAuthor = Author::where('email', $authorData['email'])->first();
                if ($globalAuthor) {
                    $globalAuthor->increment('total_articles_count');
                } else {
                    Author::create([
                        'full_name' => trim("{$authorData['first_name']} {$authorData['last_name']}"),
                        'email' => $authorData['email'],
                        'institution' => $authorData['institution'],
                        'country' => $authorData['country'],
                        'orcid' => $authorData['orcid'] ?? null,
                        'total_articles_count' => 1,
                    ]);
                }
            }

            // Save Manuscript File
            if ($request->hasFile('manuscript_file')) {
                $file = $request->file('manuscript_file');
                $ext = $file->getClientOriginalExtension();
                $filename = "{$manuscriptId}_manuscript_" . time() . ".{$ext}";
                $path = $file->storeAs('manuscripts', $filename);

                ArticleFile::create([
                    'article_id' => $article->id,
                    'file_type' => 'manuscript',
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }

            // Save Cover Letter if present
            if ($request->hasFile('cover_letter')) {
                $cfile = $request->file('cover_letter');
                $cext = $cfile->getClientOriginalExtension();
                $cfilename = "{$manuscriptId}_cover_letter_" . time() . ".{$cext}";
                $cpath = $cfile->storeAs('manuscripts', $cfilename);

                ArticleFile::create([
                    'article_id' => $article->id,
                    'file_type' => 'cover_letter',
                    'original_name' => $cfile->getClientOriginalName(),
                    'file_path' => $cpath,
                    'file_size' => $cfile->getSize(),
                    'mime_type' => $cfile->getClientMimeType(),
                ]);
            }

            // Save Supplementary Files if present
            if ($request->hasFile('supplementary_files')) {
                foreach ($request->file('supplementary_files') as $sfile) {
                    if ($sfile->isValid()) {
                        $sext = $sfile->getClientOriginalExtension();
                        $sfilename = "{$manuscriptId}_supp_" . uniqid() . ".{$sext}";
                        $spath = $sfile->storeAs('manuscripts', $sfilename);

                        ArticleFile::create([
                            'article_id' => $article->id,
                            'file_type' => 'supplementary',
                            'original_name' => $sfile->getClientOriginalName(),
                            'file_path' => $spath,
                            'file_size' => $sfile->getSize(),
                            'mime_type' => $sfile->getClientMimeType(),
                        ]);
                    }
                }
            }

            // Create initial timeline entry
            ArticleTimeline::create([
                'article_id' => $article->id,
                'status_from' => null,
                'status_to' => 'Submitted',
                'comment' => 'Manuscript submitted successfully by author.',
                'created_by' => 'Author (Public Form)',
            ]);

            DB::commit();

            return redirect()->route('submission.success', ['manuscript_id' => $manuscriptId]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Submission failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $manuscriptId = $request->query('manuscript_id');
        $article = Article::where('manuscript_id', $manuscriptId)->firstOrFail();
        return view('frontend.submission_success', compact('article'));
    }
}
