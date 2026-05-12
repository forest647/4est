<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('creation_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creation_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description');
            $table->timestamps();

            $table->unique(['creation_id', 'locale']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creation_translations');
    }
};
