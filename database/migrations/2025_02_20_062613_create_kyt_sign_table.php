<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kyt_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyt_report_id')->constrained('kyt_reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('signEncryp'); // Kolom untuk menyimpan data yang terenkripsi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kyt_sign');
    }
};
