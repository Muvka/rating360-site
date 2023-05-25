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
        Schema::create('user_rating_template_competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_rating_template_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->index('user_rating_competence_user_rating_template_id_foreign');
            $table->string('name', 255);
            $table->unsignedInteger('sort')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rating_template_competences');
    }
};
