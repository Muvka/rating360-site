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
        Schema::create('rating_matrix_template_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_matrix_template_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->index('rt_matrix_template_client_matrix_template_foreign_id');
            $table->foreignId('rating_employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('outer')
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_matrix_template_clients');
    }
};
