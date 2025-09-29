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
        Schema::table('cotisations', function (Blueprint $table) {
            $table->string('dette_last_year')->after('for_year')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotisations', function (Blueprint $table) {
            $table->dropColumn('dette_last_year');
        });
    }
};
