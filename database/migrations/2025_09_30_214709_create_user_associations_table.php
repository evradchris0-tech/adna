<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('association_id'); // ✅ SINGULAR (pas associations_id)
            $table->boolean('is_primary')->default(false);
            $table->string('role_in_association')->nullable();
            $table->timestamps();

            // Clés étrangères
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->foreign('association_id')
                ->references('id')
                ->on('associations')
                ->onDelete('cascade');

            // Index unique pour éviter les doublons
            $table->unique(['user_id', 'association_id']);
            
            // Index pour les recherches
            $table->index('user_id');
            $table->index('association_id');
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_associations');
    }
};