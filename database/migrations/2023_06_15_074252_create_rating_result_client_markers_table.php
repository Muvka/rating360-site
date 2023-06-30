<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rating_result_client_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_result_client_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('competence', 255)
                ->index();
            $table->string('value', 128)
                ->index()
                ->nullable();
            $table->text('text');
            $table->tinyInteger('rating')
                ->nullable();
            $table->text('answer')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_result_client_markers');
    }
};
