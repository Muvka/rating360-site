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
        Schema::create('company_direction_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('company_direction_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
//                ->index('employee_direction_employee_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_direction_employee');
    }
};
