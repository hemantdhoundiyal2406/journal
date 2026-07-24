<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('manuscript_id')->unique();
            $table->string('title');
            $table->string('running_title')->nullable();
            $table->string('category')->default('General'); // Multidisciplinary, Engineering, Medical, Social Sciences, etc.
            $table->string('article_type')->default('Research Paper'); // Research Paper, Review Paper, Case Study, Short Communication
            $table->text('abstract');
            $table->text('keywords'); // Comma-separated or tag format
            $table->string('status')->default('Submitted'); // Submitted, Screening, Under Review, Revision Required, Revised Received, Accepted, Rejected, Published
            $table->foreignId('volume_id')->nullable()->constrained('volumes')->onDelete('set null');
            $table->foreignId('issue_id')->nullable()->constrained('issues')->onDelete('set null');
            $table->string('doi')->nullable()->unique();
            $table->string('start_page')->nullable();
            $table->string('end_page')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->text('admin_notes')->nullable();
            $table->string('certificate_token')->unique()->nullable();
            $table->timestamps();

            $table->index(['status', 'category']);
            $table->index('manuscript_id');
            $table->index('doi');
        });

        Schema::create('article_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->string('institution');
            $table->string('country');
            $table->string('orcid')->nullable();
            $table->boolean('is_corresponding')->default(false);
            $table->integer('order')->default(1);
            $table->timestamps();

            $table->index('email');
        });

        Schema::create('article_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('file_type'); // manuscript, supplementary, cover_letter, image
            $table->string('original_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('mime_type');
            $table->timestamps();
        });

        Schema::create('article_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('status_from')->nullable();
            $table->string('status_to');
            $table->text('comment')->nullable();
            $table->string('created_by')->default('System');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_timelines');
        Schema::dropIfExists('article_files');
        Schema::dropIfExists('article_authors');
        Schema::dropIfExists('articles');
    }
};
