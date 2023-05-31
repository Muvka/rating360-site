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
        Schema::create('user_rating_matrix_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_rating_matrix_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->index('user_rating_template_user_rating_matrix_id_foreign');
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('division', 128);
            $table->string('subdivision', 128);
            $table->string('position', 128);
            $table->string('level', 128);
            $table->string('company', 128);
            $table->string('city', 128);
//            $table->foreignId('direct_manager_id')
//                ->constrained()
//                ->cascadeOnUpdate();
//            $table->foreignId('functional_manager_id')
//                ->nullable()
//                ->constrained()
//                ->cascadeOnUpdate();
            $table->unsignedInteger('sort')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rating_matrix_templates');
    }
};
