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
        Schema::create('company_employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 64)
                ->index();
            $table->string('last_name', 64)
                ->nullable()
                ->index();
            $table->string('middle_name', 64)
                ->nullable()
                ->index();
            $table->string('full_name', 200)
                ->virtualAs("TRIM(CONCAT_WS(' ',last_name,first_name,middle_name))")
                ->index();
            $table->string('email')
                ->unique();
            $table->timestamp('email_verified_at')
                ->nullable();
            $table->string('password')
                ->nullable();
            $table->boolean('is_admin')
                ->default(false);
            $table->foreignId('direct_manager_id')
                ->nullable()
                ->references('id')
                ->on('company_employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('functional_manager_id')
                ->nullable()
                ->references('id')
                ->on('company_employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('city_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('company_division_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('company_subdivision_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('company_position_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('company_level_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_employees');
    }
};
