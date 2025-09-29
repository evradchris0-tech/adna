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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paroissiens_id');
            $table->unsignedBigInteger('engagement_id');
            $table->string('type');
            $table->string('somme');
            $table->foreign('paroissiens_id')->references('id')->on('paroissiens')->onDelete('cascade');
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
