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
        Schema::create('selectors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('selector');
            $table->foreignId('selector_type_id')->constrained('selector_types');
            $table->foreignId('project_id')->constrained('projects');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selectors');
    }
};
