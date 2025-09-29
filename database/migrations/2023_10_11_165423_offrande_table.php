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
        Schema::create('offrandes', function (Blueprint $table) {
            $table->id();
            $table->integer('somme');
            $table->unsignedBigInteger('association_id');
            $table->date('offrande_day');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offrandes');
    }
};
