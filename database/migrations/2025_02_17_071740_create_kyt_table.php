<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kyt_reports', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // Relasi ke tabel companies
            $table->foreignId('departement_id')->nullable()->constrained('departments')->onDelete('set null'); // Relasi ke tabel departments (nullable)
            $table->string('projectTitle')->nullable(); // Nama project
            $table->json('instructors')->nullable(); // Data instructor dalam bentuk JSON
            $table->json('attendants')->nullable(); // Data attendant dalam bentuk JSON
            $table->tinyInteger('status')->default(0); // 0 = Pending, 1 = Checked, 2 = Reviewed, 3 = Approved
            $table->timestamps(); // Created_at & Updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyt_reports');
    }
};
