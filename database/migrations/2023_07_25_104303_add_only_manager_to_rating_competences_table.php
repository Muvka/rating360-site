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
        Schema::table('rating_competences', function (Blueprint $table) {
            $table->boolean('manager_only')
                ->default(false)
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rating_competences', function (Blueprint $table) {
            $table->dropColumn('manager_only');
        });
    }
};
