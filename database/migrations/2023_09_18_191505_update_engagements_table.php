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
            $table->dropColumn('periode');
            $table->date('periode_start')->after("associations_id");
            $table->date('periode_end')->after("periode_start");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('engagements', function (Blueprint $table) {
            $table->string('periode');
            $table->dropColumn('periode_start');
            $table->dropColumn('periode_end');
        });
    }
};
