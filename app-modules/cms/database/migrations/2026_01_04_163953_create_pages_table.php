<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('pages')
                ->nullOnDelete();
            $table->foreignId('translation_origin_id')
                ->nullable()
                ->constrained('pages')
                ->nullOnDelete();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('lang', 10)->default('en')->index();
            $table->string('status')->default('draft')->index();
            $table->json('content');
            $table->longText('searchable_content')->nullable();
            $table->string('theme')->default('default');
            $table->boolean('is_landing')->default(false);

            // SEO
            $table->json('seo_metadata')->nullable();

            $table->dateTime('published_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['lang', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
