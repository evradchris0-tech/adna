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
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paroissiens_id');
            $table->unsignedBigInteger('associations_id');
            $table->string('periode');
            $table->string('dime');
            $table->string('offrande');
            $table->string('cotisation');
            $table->foreign('paroissiens_id')->references('id')->on('paroissiens')->onDelete('cascade');
            $table->foreign('associations_id')->references('id')->on('associations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagements');
    }
};
