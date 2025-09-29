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
        Schema::table('paroissiens', function (Blueprint $table) {
            $table->date('confirm_date')->nullable()->change();
            $table->date('adhesion_date')->nullable()->change();
            $table->date('baptise_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paroissiens', function (Blueprint $table) {
        });
    }
};
