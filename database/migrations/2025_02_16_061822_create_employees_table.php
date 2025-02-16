<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('user_id')->nullable()->unique(); // Relasi ke tabel users
            $table->string('nik', 20)->unique(); // NIK sebagai nomor identitas
            $table->string('name', 100);
            $table->string('position', 50);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('departement_id');
            $table->integer('status_id')->default(1);
            $table->timestamps();

            // Foreign Key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
