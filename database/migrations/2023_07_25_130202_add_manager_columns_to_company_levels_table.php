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
        Schema::table('company_levels', function (Blueprint $table) {
            $table->boolean('is_manager')
                ->default(false)
                ->after('name');
            $table->boolean('requires_manager')
                ->default(true)
                ->after('is_manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_levels', function (Blueprint $table) {
            $table->dropColumn('is_manager');
            $table->dropColumn('requires_manager');
        });
    }
};
