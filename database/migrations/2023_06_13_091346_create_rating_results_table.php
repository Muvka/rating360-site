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
        Schema::create('rating_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('company_employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('city', 128)
                ->index();
            $table->string('company', 255)
                ->index();
            $table->string('division', 255)
                ->index();
            $table->string('subdivision', 255)
                ->index();
//            $table->text('direction');
            $table->string('position', 255)
                ->index();
            $table->string('level', 64)
                ->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_results');
    }
};
