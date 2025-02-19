<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kyt_reports', function (Blueprint $table) {
            $table->id(); // Primary Key (Auto-increment)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang membuat laporan
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // Perusahaan
            $table->foreignId('departement_id')->nullable()->constrained('departments')->onDelete('set null'); // Departemen
            
            $table->date('date'); // Tanggal laporan
            $table->string('projectTitle'); // Nama proyek
            $table->integer('shift')->nullable();
            $table->time('workingStart'); // Jam kerja mulai
            $table->time('workingEnd'); // Jam kerja selesai

            $table->json('instructors'); // Menyimpan array instruktur (NIK)
            $table->json('attendants'); // Menyimpan array peserta (NIK)

            $table->text('potentialDangerous')->nullable();
            $table->text('mostDanger')->nullable();
            $table->text('countermeasures')->nullable();
            $table->text('{keyWord}')->nullable();

            $table->unsignedBigInteger('reviewedBy')->nullable();
            $table->unsignedBigInteger('approvedBy1')->nullable();
            $table->unsignedBigInteger('approvedBy2')->nullable();

            $table->tinyInteger('status')->default(0); // 0 = Pending, 1 = Checked, 2 = Reviewed, 3 = Approved

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kyt_reports');
    }
};
