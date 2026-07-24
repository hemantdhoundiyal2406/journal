<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('institution')->nullable();
            $table->string('country')->nullable();
            $table->string('orcid')->nullable();
            $table->integer('total_articles_count')->default(0);
            $table->integer('published_articles_count')->default(0);
            $table->timestamps();
        });

        Schema::create('editorial_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('designation'); // Editor-in-Chief, Associate Editor, Advisory Board, etc.
            $table->string('university');
            $table->string('country');
            $table->text('biography')->nullable();
            $table->string('orcid')->nullable();
            $table->string('google_scholar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('reviewers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('expertise');
            $table->string('university');
            $table->string('country');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviewers');
        Schema::dropIfExists('editorial_members');
        Schema::dropIfExists('authors');
    }
};
