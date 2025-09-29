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
        Schema::create('paroissiens', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('genre');
            $table->date('birthdate');
            $table->string('birthplace');
            $table->string('email');
            $table->string('school_level');
            $table->string('address');
            $table->string('phone');
            $table->string('old_matricule');
            $table->string('new_matricule');
            $table->unsignedBigInteger('association_id');
            $table->string('categorie');
            $table->date('confirm_date');
            $table->date('adhesion_date');
            $table->date('baptise_date');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('wife_or_husban_name');
            $table->string('marital_status');
            $table->string('nb_children');
            $table->string('job');
            $table->string('job_poste');


            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paroissiens');
    }
};
