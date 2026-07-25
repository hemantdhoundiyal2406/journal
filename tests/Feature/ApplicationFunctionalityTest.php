<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleFile;
use App\Models\ArticleTimeline;
use App\Models\CmsPage;
use App\Models\EditorialMember;
use App\Models\EmailTemplate;
use App\Models\Issue;
use App\Models\JournalSetting;
use App\Models\Reviewer;
use App\Models\User;
use App\Models\Volume;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_website_pages_search_contact_and_downloads_work(): void
    {
        $article = Article::where('status', 'Published')->firstOrFail();
        $startingViews = $article->view_count;

        $this->get(route('home'))->assertOk()->assertSee('International Journal', false);
        $this->get(route('current-issue'))->assertOk();
        $this->get(route('archives'))->assertOk();
        $this->get(route('editorial-board'))->assertOk();
        $this->get(route('cms.page', 'about'))->assertOk()->assertSee('About the Journal');
        $this->get(route('issue.detail', Issue::where('is_published', true)->firstOrFail()))->assertOk();

        $this->get(route('article.detail', $article))->assertOk()->assertSee($article->title);
        $this->assertSame($startingViews + 1, $article->fresh()->view_count);

        $this->get(route('search', ['q' => 'Quantum']))
            ->assertOk()
            ->assertSee('Quantum Neural Networks');

        $this->getJson(route('search', ['q' => 'Quantum']), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonStructure(['html', 'total']);

        $this->get(route('certificate.verify', $article->certificate_token))
            ->assertOk()
            ->assertSee($article->certificate_token);

        $this->get(route('seo.sitemap'))->assertOk()->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $this->get(route('seo.robots'))->assertOk()->assertSee('Disallow: /admin/');

        $this->post(route('contact.submit'), [
            'name' => 'Functional Tester',
            'email' => 'tester@example.com',
            'subject' => 'Question',
            'message' => 'This is a valid contact message for the editorial office.',
        ])->assertRedirect()->assertSessionHas('success');

        Storage::fake('local');
        Storage::disk('local')->put('manuscripts/public-paper.pdf', 'PDF body');
        ArticleFile::create([
            'article_id' => $article->id,
            'file_type' => 'manuscript',
            'original_name' => 'public-paper.pdf',
            'file_path' => 'manuscripts/public-paper.pdf',
            'file_size' => 8,
            'mime_type' => 'application/pdf',
        ]);

        $this->get(route('article.download', $article))
            ->assertOk()
            ->assertDownload('public-paper.pdf');
    }

    public function test_public_submission_form_validates_checklist_and_creates_manuscript(): void
    {
        Storage::fake('local');

        $payload = $this->validSubmissionPayload();

        $this->from(route('submission.form'))
            ->post(route('submission.submit'), array_merge($payload, [
                'manuscript_file' => UploadedFile::fake()->create('manuscript.pdf', 48, 'application/pdf'),
            ]))
            ->assertRedirect(route('submission.form'))
            ->assertSessionHasErrors([
                'originality_confirmed',
                'authors_approved',
                'references_confirmed',
            ]);

        $response = $this->post(route('submission.submit'), array_merge($payload, [
            'manuscript_file' => UploadedFile::fake()->create('manuscript.pdf', 48, 'application/pdf'),
            'cover_letter' => UploadedFile::fake()->create('cover-letter.docx', 16, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            'supplementary_files' => [
                UploadedFile::fake()->create('dataset.zip', 20, 'application/zip'),
            ],
            'originality_confirmed' => '1',
            'authors_approved' => '1',
            'references_confirmed' => '1',
        ]));

        $article = Article::where('title', $payload['title'])->firstOrFail();

        $response->assertRedirect(route('submission.success', ['manuscript_id' => $article->manuscript_id]));
        $this->get(route('submission.success', ['manuscript_id' => $article->manuscript_id]))
            ->assertOk()
            ->assertSee($article->manuscript_id);

        $this->assertSame('Submitted', $article->status);
        $this->assertCount(2, $article->authors);
        $this->assertTrue($article->authors()->where('email', 'lead.author@example.com')->first()->is_corresponding);
        $this->assertDatabaseHas('article_timelines', [
            'article_id' => $article->id,
            'status_to' => 'Submitted',
        ]);

        foreach ($article->files as $file) {
            Storage::disk('local')->assertExists($file->file_path);
        }
    }

    public function test_admin_auth_pages_and_article_tabs_work(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        $this->from(route('admin.login'))
            ->post(route('admin.login.submit'), [
                'email' => 'admin@ijaser-journal.org',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@ijaser-journal.org',
            'password' => 'admin@12345',
        ])->assertRedirect(route('admin.dashboard'));

        $article = Article::firstOrFail();

        foreach ($this->adminGetRoutes($article) as $route) {
            $this->get($route)->assertOk();
        }

        foreach (['overview', 'authors', 'files', 'notes', 'timeline', 'letters', 'emails', 'publication', 'doi'] as $tab) {
            $this->get(route('admin.articles.show', ['id' => $article->id, 'tab' => $tab]))
                ->assertOk()
                ->assertSee($article->manuscript_id);
        }

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_article_actions_upload_download_delete_pdf_and_email_work(): void
    {
        Storage::fake('local');
        $admin = User::where('email', 'admin@ijaser-journal.org')->firstOrFail();
        $this->actingAs($admin);

        $article = Article::where('status', 'Submitted')->firstOrFail();
        $volume = Volume::firstOrFail();
        $issue = Issue::where('volume_id', $volume->id)->firstOrFail();

        $this->post(route('admin.articles.update-status', $article), [
            'status' => 'Under Review',
            'comment' => 'Functional test status update.',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertSame('Under Review', $article->fresh()->status);
        $this->assertDatabaseHas('article_timelines', [
            'article_id' => $article->id,
            'status_to' => 'Under Review',
        ]);

        $this->post(route('admin.articles.update-notes', $article), [
            'admin_notes' => 'Functional test note.',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertSame('Functional test note.', $article->fresh()->admin_notes);

        $this->post(route('admin.articles.update-authors', $article), [
            'corresponding_index' => '1',
            'authors' => [
                [
                    'first_name' => 'First',
                    'last_name' => 'Author',
                    'email' => 'first@example.com',
                    'institution' => 'Test University',
                    'country' => 'India',
                ],
                [
                    'first_name' => 'Second',
                    'last_name' => 'Author',
                    'email' => 'second@example.com',
                    'institution' => 'Peer Institute',
                    'country' => 'India',
                    'orcid' => '0000-0000-0000-0001',
                ],
            ],
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertTrue($article->fresh()->authors()->where('email', 'second@example.com')->first()->is_corresponding);

        $this->post(route('admin.articles.update-publication', $article), [
            'volume_id' => $volume->id,
            'issue_id' => $issue->id,
            'start_page' => '101',
            'end_page' => '110',
            'doi' => '10.1234/functional-publication',
            'published_at' => '2026-07-25',
        ])->assertRedirect()->assertSessionHas('success');

        $article->refresh();
        $this->assertSame($volume->id, $article->volume_id);
        $this->assertSame($issue->id, $article->issue_id);
        $this->assertSame('101', $article->start_page);
        $this->assertSame('110', $article->end_page);

        $this->post(route('admin.articles.update-publication', $article), [
            'doi' => '10.1234/doi-only-update',
        ])->assertRedirect()->assertSessionHas('success');

        $article->refresh();
        $this->assertSame($volume->id, $article->volume_id);
        $this->assertSame($issue->id, $article->issue_id);
        $this->assertSame('101', $article->start_page);
        $this->assertSame('110', $article->end_page);
        $this->assertSame('10.1234/doi-only-update', $article->doi);

        $this->post(route('admin.articles.upload-file', $article), [
            'file_type' => 'supplementary',
            'file' => UploadedFile::fake()->create('admin-supplement.pdf', 12, 'application/pdf'),
        ])->assertRedirect()->assertSessionHas('success');

        $file = $article->fresh()->files()->where('file_type', 'supplementary')->firstOrFail();
        Storage::disk('local')->assertExists($file->file_path);

        $this->get(route('admin.articles.download-file', ['id' => $article->id, 'fileId' => $file->id]))
            ->assertOk()
            ->assertDownload('admin-supplement.pdf');

        $this->delete(route('admin.articles.delete-file', ['id' => $article->id, 'fileId' => $file->id]))
            ->assertRedirect()
            ->assertSessionHas('success');
        Storage::disk('local')->assertMissing($file->file_path);
        $this->assertDatabaseMissing('article_files', ['id' => $file->id]);

        foreach (['acceptance', 'rejection', 'revision', 'publication', 'copyright'] as $type) {
            $this->get(route('admin.articles.letter', ['id' => $article->id, 'type' => $type]))
                ->assertOk()
                ->assertHeader('Content-Type', 'application/pdf');
        }

        $this->get(route('admin.articles.certificate', $article))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->post(route('admin.articles.send-email', $article), [
            'subject' => 'Functional email',
            'message' => 'This is a simulated email notification.',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertTrue(ArticleTimeline::where('article_id', $article->id)->where('comment', 'like', '%Functional email%')->exists());
    }

    public function test_admin_catalog_options_and_settings_work(): void
    {
        Storage::fake('public');
        $this->actingAs(User::where('email', 'admin@ijaser-journal.org')->firstOrFail());

        $this->post(route('admin.announcements.store'), [
            'type' => 'latest_news',
            'title' => 'Functional Announcement',
            'content' => 'Functional announcement content.',
            'link' => 'https://example.com/news',
            'is_active' => '1',
        ])->assertRedirect()->assertSessionHas('success');
        $announcement = Announcement::where('title', 'Functional Announcement')->firstOrFail();

        $this->post(route('admin.announcements.toggle', $announcement))->assertRedirect()->assertSessionHas('success');
        $this->assertFalse($announcement->fresh()->is_active);

        $this->delete(route('admin.announcements.destroy', $announcement))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);

        $this->post(route('admin.reviewers.store'), [
            'name' => 'Functional Reviewer',
            'email' => 'reviewer@example.com',
            'expertise' => 'Laravel testing',
            'university' => 'QA University',
            'country' => 'India',
            'notes' => 'Available.',
        ])->assertRedirect()->assertSessionHas('success');
        $reviewer = Reviewer::where('email', 'reviewer@example.com')->firstOrFail();

        $this->get(route('admin.reviewers.index', ['search' => 'Laravel testing']))
            ->assertOk()
            ->assertSee('Functional Reviewer');

        $this->delete(route('admin.reviewers.destroy', $reviewer))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('reviewers', ['id' => $reviewer->id]);

        $this->post(route('admin.editorial.store'), [
            'name' => 'Functional Editor',
            'designation' => 'Associate Editor',
            'university' => 'Editorial University',
            'country' => 'India',
            'biography' => 'Functional biography.',
            'orcid' => '0000-0000-0000-0002',
            'google_scholar' => 'https://scholar.google.com',
            'sort_order' => 9,
        ])->assertRedirect()->assertSessionHas('success');
        $member = EditorialMember::where('name', 'Functional Editor')->firstOrFail();

        $this->delete(route('admin.editorial.destroy', $member))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('editorial_members', ['id' => $member->id]);

        $this->post(route('admin.volumes.store'), [
            'volume_number' => '99',
            'year' => 2026,
            'title' => 'Functional Volume',
            'description' => 'Functional volume description.',
        ])->assertRedirect()->assertSessionHas('success');
        $volume = Volume::where('volume_number', '99')->firstOrFail();

        $this->post(route('admin.issues.store'), [
            'volume_id' => $volume->id,
            'issue_number' => '7',
            'title' => 'Functional Issue',
            'publication_month' => 'July',
            'publication_year' => 2026,
        ])->assertRedirect()->assertSessionHas('success');
        $issue = Issue::where('volume_id', $volume->id)->where('issue_number', '7')->firstOrFail();

        $article = Article::where('status', 'Submitted')->firstOrFail();
        $article->update(['issue_id' => $issue->id]);

        $this->post(route('admin.issues.publish', $issue))->assertRedirect()->assertSessionHas('success');
        $this->assertTrue($issue->fresh()->is_published);
        $this->assertSame('Published', $article->fresh()->status);

        $page = CmsPage::where('slug', 'about')->firstOrFail();
        $this->post(route('admin.cms.update', $page), [
            'title' => 'Updated About',
            'meta_title' => 'Updated About Meta',
            'meta_description' => 'Updated description',
            'meta_keywords' => 'updated,about',
            'content' => '<p>Updated functional content.</p>',
            'is_active' => '1',
        ])->assertRedirect(route('admin.cms.index'))->assertSessionHas('success');
        $this->assertSame('Updated About', $page->fresh()->title);

        $template = EmailTemplate::firstOrFail();
        $this->post(route('admin.email-templates.update', $template), [
            'subject' => 'Updated template subject',
            'body_html' => '<p>Updated template body.</p>',
        ])->assertRedirect(route('admin.email-templates.index'))->assertSessionHas('success');
        $this->assertSame('Updated template subject', $template->fresh()->subject);

        $published = Article::where('status', 'Published')->firstOrFail();
        $this->post(route('admin.published.update-doi', $published), [
            'doi' => '10.1234/published-doi',
            'start_page' => '200',
            'end_page' => '210',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertSame('10.1234/published-doi', $published->fresh()->doi);

        $this->post(route('admin.settings.update'), [
            'journal_name' => 'Functional Journal',
            'manuscript_id_prefix' => 'FJ',
            'tagline' => 'Functional tagline',
            'contact_email' => 'contact@example.com',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertSame('Functional Journal', JournalSetting::getByKey('journal_name'));
        $this->assertSame('FJ', JournalSetting::getByKey('manuscript_id_prefix'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validSubmissionPayload(): array
    {
        return [
            'title' => 'Functional Test Manuscript Submission',
            'running_title' => 'Functional Submission',
            'category' => 'Computer Science & Security',
            'article_type' => 'Research Paper',
            'abstract' => 'This abstract is intentionally long enough to satisfy validation and describes a complete functional testing submission workflow.',
            'keywords' => 'functional testing, laravel, journal',
            'corresponding_author_index' => '0',
            'authors' => [
                [
                    'first_name' => 'Lead',
                    'last_name' => 'Author',
                    'email' => 'lead.author@example.com',
                    'mobile' => '+911234567890',
                    'institution' => 'Functional Institute',
                    'country' => 'India',
                    'orcid' => '0000-0000-0000-0003',
                ],
                [
                    'first_name' => 'Co',
                    'last_name' => 'Author',
                    'email' => 'co.author@example.com',
                    'institution' => 'Functional Institute',
                    'country' => 'India',
                ],
            ],
            'author_notes' => 'Please route this to the functional testing editor.',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function adminGetRoutes(Article $article): array
    {
        return [
            route('admin.dashboard'),
            route('admin.articles.index'),
            route('admin.articles.index', ['search' => $article->manuscript_id]),
            route('admin.inbox.index'),
            route('admin.authors.index'),
            route('admin.authors.show', \App\Models\Author::firstOrFail()),
            route('admin.volumes.index'),
            route('admin.published.index'),
            route('admin.editorial.index'),
            route('admin.reviewers.index'),
            route('admin.reviewers.index', ['search' => 'Artificial']),
            route('admin.cms.index'),
            route('admin.cms.edit', CmsPage::firstOrFail()),
            route('admin.email-templates.index'),
            route('admin.email-templates.edit', EmailTemplate::firstOrFail()),
            route('admin.announcements.index'),
            route('admin.settings.index'),
        ];
    }
}
