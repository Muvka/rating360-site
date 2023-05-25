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
        Schema::create('user_rating_template_competence_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_rating_template_competence_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->index('user_rating_marker_user_rating_competence_id_foreign');
            $table->text('text');
            $table->set('value', ['respect', 'responsibility', 'development', 'team_leadership'])
                ->nullable();
            $table->set('answer_type', ['default', 'text'])
                ->default('default');
            $table->unsignedInteger('sort')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rating_template_competence_markers');
    }
};
