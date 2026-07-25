<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleFile;
use App\Models\ArticleTimeline;
use App\Models\EmailTemplate;
use App\Models\Issue;
use App\Models\Volume;
use App\Services\LetterGeneratorService;
use App\Services\CertificateGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['authors', 'volume', 'issue']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('manuscript_id', 'LIKE', "%{$search}%")
                    ->orWhere('doi', 'LIKE', "%{$search}%")
                    ->orWhereHas('authors', function ($aq) use ($search) {
                        $aq->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $articles = $query->orderBy('id', 'desc')->paginate(15)->appends($request->all());
        $volumes = Volume::all();
        $issues = Issue::where('is_published', true)->get();

        return view('admin.articles.index', compact('articles', 'volumes', 'issues'));
    }

    /**
     * Dedicated Single Article Management Screen (All 9 Tabs)
     */
    public function show($id, Request $request)
    {
        $article = Article::with(['authors', 'files', 'timelines', 'volume', 'issue'])->findOrFail($id);
        $volumes = Volume::with('issues')->get();
        $issues = Issue::all();
        $emailTemplates = EmailTemplate::all();
        $activeTab = $request->query('tab', 'overview');

        return view('admin.articles.manage', compact('article', 'volumes', 'issues', 'emailTemplates', 'activeTab'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'nullable|string|max:1000',
        ]);

        $article = Article::findOrFail($id);
        $oldStatus = $article->status;
        $article->status = $request->status;

        if ($request->status === 'Published' && !$article->published_at) {
            $article->published_at = now();
        }

        $article->save();

        ArticleTimeline::create([
            'article_id' => $article->id,
            'status_from' => $oldStatus,
            'status_to' => $request->status,
            'comment' => $request->comment ?? "Status updated to {$request->status} by admin.",
            'created_by' => 'Editorial Admin',
        ]);

        return back()->with('success', "Article status updated to '{$request->status}' successfully.");
    }

    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $article = Article::findOrFail($id);
        $article->admin_notes = $request->admin_notes;
        $article->save();

        return back()->with('success', 'Internal notes saved successfully.');
    }

    public function updatePublication(Request $request, $id)
    {
        $request->validate([
            'volume_id' => 'nullable|exists:volumes,id',
            'issue_id' => 'nullable|exists:issues,id',
            'start_page' => 'nullable|string|max:20',
            'end_page' => 'nullable|string|max:20',
            'doi' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);

        $article = Article::findOrFail($id);

        $article->update([
            'volume_id' => $request->has('volume_id') ? $request->input('volume_id') : $article->volume_id,
            'issue_id' => $request->has('issue_id') ? $request->input('issue_id') : $article->issue_id,
            'start_page' => $request->has('start_page') ? $request->input('start_page') : $article->start_page,
            'end_page' => $request->has('end_page') ? $request->input('end_page') : $article->end_page,
            'doi' => $request->has('doi') ? $request->input('doi') : $article->doi,
            'published_at' => $request->has('published_at') && $request->filled('published_at')
                ? \Carbon\Carbon::parse($request->input('published_at'))
                : $article->published_at,
        ]);

        return back()->with('success', 'Publication details updated successfully.');
    }

    public function updateAuthors(Request $request, $id)
    {
        $request->validate([
            'authors' => 'required|array|min:1',
            'authors.*.first_name' => 'required|string|max:100',
            'authors.*.last_name' => 'required|string|max:100',
            'authors.*.email' => 'required|email|max:150',
            'authors.*.institution' => 'required|string|max:250',
            'authors.*.country' => 'required|string|max:100',
            'authors.*.orcid' => 'nullable|string|max:50',
        ]);

        $article = Article::findOrFail($id);
        
        DB::transaction(function () use ($article, $request) {
            $article->authors()->delete();
            $corrIndex = (int) $request->input('corresponding_index', 0);

            foreach ($request->authors as $idx => $data) {
                ArticleAuthor::create([
                    'article_id' => $article->id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'institution' => $data['institution'],
                    'country' => $data['country'],
                    'orcid' => $data['orcid'] ?? null,
                    'is_corresponding' => ($idx === $corrIndex),
                    'order' => $idx + 1,
                ]);
            }
        });

        return back()->with('success', 'Authors updated successfully.');
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file_type' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,zip,jpg,png|max:20480',
        ]);

        $article = Article::findOrFail($id);
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $filename = "{$article->manuscript_id}_{$request->file_type}_" . time() . ".{$ext}";
        $path = $file->storeAs('manuscripts', $filename);

        ArticleFile::create([
            'article_id' => $article->id,
            'file_type' => $request->file_type,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function downloadFile($id, $fileId)
    {
        $file = ArticleFile::where('article_id', $id)->where('id', $fileId)->firstOrFail();

        if (!Storage::disk('local')->exists($file->file_path)) {
            return back()->with('error', 'Requested article file not found on server.');
        }

        return Storage::disk('local')->download($file->file_path, $file->original_name);
    }

    public function deleteFile($id, $fileId)
    {
        $file = ArticleFile::where('article_id', $id)->where('id', $fileId)->firstOrFail();
        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    public function generateLetter($id, $type)
    {
        $article = Article::findOrFail($id);
        $service = new LetterGeneratorService();
        $pdf = $service->generate($article, $type);

        return $pdf->download("{$article->manuscript_id}_letter_{$type}.pdf");
    }

    public function generateCertificate($id)
    {
        $article = Article::findOrFail($id);
        $service = new CertificateGeneratorService();
        $pdf = $service->generate($article);

        return $pdf->download("{$article->manuscript_id}_publication_certificate.pdf");
    }

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:250',
            'message' => 'required|string',
        ]);

        $article = Article::with('authors')->findOrFail($id);
        $corrAuthor = $article->correspondingAuthor ?? $article->authors->first();

        // Log timeline note for sent email
        ArticleTimeline::create([
            'article_id' => $article->id,
            'status_from' => $article->status,
            'status_to' => $article->status,
            'comment' => "Email sent to {$corrAuthor->email} with Subject: '{$request->subject}'",
            'created_by' => 'Editorial System',
        ]);

        return back()->with('success', "Simulated email sent to {$corrAuthor->email} successfully.");
    }
}
