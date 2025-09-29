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
        Schema::table('engagements', function (Blueprint $table) {
            $table->float('available_cotisation')->after("cotisation")->nullable()->default(0);
            $table->float('available_dime')->after("cotisation")->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->dropColumn('available_cotisation');
            $table->dropColumn('available_dime');
        });
    }
};
