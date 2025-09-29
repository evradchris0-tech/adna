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
            $table->string('service_place')->nullable();
            $table->string('situation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paroissiens', function (Blueprint $table) {
            $table->removeColumn('service_place');
            $table->removeColumn('situation');
        });
    }
};
