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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hoa_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('facture');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('hoa_id')->references('id')->on('hoas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
