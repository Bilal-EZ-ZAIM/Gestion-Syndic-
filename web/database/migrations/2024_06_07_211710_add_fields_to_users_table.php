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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('apartment_number')->nullable();
            $table->unsignedBigInteger('hoa_id')->nullable();
            $table->enum('role', ['cindik', 'resident', 'admin', 'proprietaire'])->default('resident');
            $table->text('avatar')->nullable()->change();
            $table->foreign('hoa_id')->references('id')->on('hoas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hoa_id']);

            $table->dropColumn(['phone', 'apartment_number', 'hoa_id', 'role']);
            $table->text('avatar')->change();
        });
    }
};
