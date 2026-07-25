<?php

use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminAuthorController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEditorialBoardController;
use App\Http\Controllers\Admin\AdminEmailTemplateController;
use App\Http\Controllers\Admin\AdminPublishedArticlesController;
use App\Http\Controllers\Admin\AdminReviewerController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSubmissionInboxController;
use App\Http\Controllers\Admin\AdminVolumeIssueController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

// Public Journal Website Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/article/{id}', [FrontendController::class, 'articleDetail'])->name('article.detail');
Route::get('/article/{id}/download', [FrontendController::class, 'downloadManuscript'])->name('article.download');
Route::get('/current-issue', [FrontendController::class, 'currentIssue'])->name('current-issue');
Route::get('/archives', [FrontendController::class, 'archives'])->name('archives');
Route::get('/issue/{id}', [FrontendController::class, 'issueDetail'])->name('issue.detail');
Route::get('/editorial-board', [FrontendController::class, 'editorialBoard'])->name('editorial-board');
Route::get('/page/{slug}', [FrontendController::class, 'cmsPage'])->name('cms.page');
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/verify-certificate/{token}', [FrontendController::class, 'verifyCertificate'])->name('certificate.verify');
Route::post('/contact/send', [FrontendController::class, 'contactSubmit'])->name('contact.submit');

// Public Manuscript Submission Routes
Route::get('/submit-manuscript', [SubmissionController::class, 'showForm'])->name('submission.form');
Route::post('/submit-manuscript/store', [SubmissionController::class, 'submit'])->name('submission.submit');
Route::get('/submission-success', [SubmissionController::class, 'success'])->name('submission.success');

// Dynamic SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('seo.robots');

// Admin Authentication Public Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Panel Protected Routes (Requires ID & Password Login)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Article Lifecycle & Single Article Screen
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{id}', [AdminArticleController::class, 'show'])->name('articles.show');
    Route::post('/articles/{id}/status', [AdminArticleController::class, 'updateStatus'])->name('articles.update-status');
    Route::post('/articles/{id}/notes', [AdminArticleController::class, 'updateNotes'])->name('articles.update-notes');
    Route::post('/articles/{id}/publication', [AdminArticleController::class, 'updatePublication'])->name('articles.update-publication');
    Route::post('/articles/{id}/authors', [AdminArticleController::class, 'updateAuthors'])->name('articles.update-authors');
    Route::post('/articles/{id}/file', [AdminArticleController::class, 'uploadFile'])->name('articles.upload-file');
    Route::get('/articles/{id}/file/{fileId}/download', [AdminArticleController::class, 'downloadFile'])->name('articles.download-file');
    Route::delete('/articles/{id}/file/{fileId}', [AdminArticleController::class, 'deleteFile'])->name('articles.delete-file');
    Route::get('/articles/{id}/letter/{type}', [AdminArticleController::class, 'generateLetter'])->name('articles.letter');
    Route::get('/articles/{id}/certificate', [AdminArticleController::class, 'generateCertificate'])->name('articles.certificate');
    Route::post('/articles/{id}/email', [AdminArticleController::class, 'sendEmail'])->name('articles.send-email');

    // Triage Inbox
    Route::get('/inbox', [AdminSubmissionInboxController::class, 'index'])->name('inbox.index');
    Route::post('/inbox/{id}/triage', [AdminSubmissionInboxController::class, 'triageAction'])->name('inbox.triage');

    // Authors Directory
    Route::get('/authors', [AdminAuthorController::class, 'index'])->name('authors.index');
    Route::get('/authors/{id}', [AdminAuthorController::class, 'show'])->name('authors.show');

    // Volumes & Issues
    Route::get('/volumes', [AdminVolumeIssueController::class, 'index'])->name('volumes.index');
    Route::post('/volumes/store', [AdminVolumeIssueController::class, 'storeVolume'])->name('volumes.store');
    Route::post('/issues/store', [AdminVolumeIssueController::class, 'storeIssue'])->name('issues.store');
    Route::post('/issues/{id}/publish', [AdminVolumeIssueController::class, 'publishIssue'])->name('issues.publish');

    // Published Articles & DOI
    Route::get('/published', [AdminPublishedArticlesController::class, 'index'])->name('published.index');
    Route::post('/published/{id}/doi', [AdminPublishedArticlesController::class, 'updateDoi'])->name('published.update-doi');

    // Editorial Board
    Route::get('/editorial', [AdminEditorialBoardController::class, 'index'])->name('editorial.index');
    Route::post('/editorial/store', [AdminEditorialBoardController::class, 'store'])->name('editorial.store');
    Route::delete('/editorial/{id}', [AdminEditorialBoardController::class, 'destroy'])->name('editorial.destroy');

    // Reviewers Database
    Route::get('/reviewers', [AdminReviewerController::class, 'index'])->name('reviewers.index');
    Route::post('/reviewers/store', [AdminReviewerController::class, 'store'])->name('reviewers.store');
    Route::delete('/reviewers/{id}', [AdminReviewerController::class, 'destroy'])->name('reviewers.destroy');

    // CMS Pages
    Route::get('/cms', [AdminCmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/{id}/edit', [AdminCmsController::class, 'edit'])->name('cms.edit');
    Route::post('/cms/{id}', [AdminCmsController::class, 'update'])->name('cms.update');

    // Email Templates
    Route::get('/email-templates', [AdminEmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{id}/edit', [AdminEmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::post('/email-templates/{id}', [AdminEmailTemplateController::class, 'update'])->name('email-templates.update');

    // Announcements
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements/store', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::post('/announcements/{id}/toggle', [AdminAnnouncementController::class, 'toggle'])->name('announcements.toggle');
    Route::delete('/announcements/{id}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [AdminSettingController::class, 'update'])->name('settings.update');
});
