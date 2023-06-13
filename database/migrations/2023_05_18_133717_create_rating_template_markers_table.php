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
        Schema::create('rating_template_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_template_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('text');
            $table->set('value', ['respect', 'responsibility', 'development', 'team_leadership'])
                ->nullable();
            $table->set('answer_type', ['default', 'text'])
                ->default('default');
            $table->unsignedInteger('sort')
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_template_markers');
    }
};
