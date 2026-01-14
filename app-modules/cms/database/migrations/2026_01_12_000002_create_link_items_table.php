<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('link_items', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('link_group_id')->constrained('link_groups')->cascadeOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('link_items')
                ->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->string('label');
            $table->string('url')->nullable();
            $table->nullableMorphs('model');
            $table->string('target')->default('self');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_items');
    }
};
