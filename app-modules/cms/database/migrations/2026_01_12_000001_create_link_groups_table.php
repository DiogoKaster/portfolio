<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('link_groups', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('position')->nullable();
            $table->string('lang')->nullable();
            $table->foreignId('translation_origin_id')
                ->nullable()
                ->constrained('link_groups')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_groups');
    }
};
