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
            $table->bigInteger('available_cotisation')->change();
            $table->bigInteger('available_dime')->change();
            $table->bigInteger('dette_cotisation')->after("cotisation")->nullable()->default(0);
            $table->bigInteger('dette_dime')->after("cotisation")->nullable()->default(0);
            $table->bigInteger('available_dette_dime')->after("dette_dime")->nullable()->default(0);
            $table->bigInteger('available_dette_cotisation')->after("dette_cotisation")->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->dropColumn('dette');
            $table->dropColumn('available_dette');
        });
    }
};
