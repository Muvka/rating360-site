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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->foreignId('rating_template_id')
                ->constrained()
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('rating_matrix_id')
                ->constrained()
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->set('status', ['draft', 'in progress', 'paused', 'closed'])
                ->default('draft');
            $table->timestamps();
            $table->dateTime('launched_at')
                ->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
