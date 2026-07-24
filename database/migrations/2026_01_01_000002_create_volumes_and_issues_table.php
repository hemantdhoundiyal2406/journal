<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volumes', function (Blueprint $table) {
            $table->id();
            $table->string('volume_number');
            $table->integer('year');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volume_id')->constrained('volumes')->onDelete('cascade');
            $table->string('issue_number');
            $table->string('title')->nullable();
            $table->string('publication_month')->nullable();
            $table->integer('publication_year');
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
        Schema::dropIfExists('volumes');
    }
};
