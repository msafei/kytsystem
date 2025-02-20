<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flow_kyt_reports', function (Blueprint $table) {
            $table->id();  // ID auto increment
            $table->enum('flowStatus', [1, 2, 3, 4])->comment('1: CHECKED, 2: REVIEWED, 3: APPROVED1, 4: APPROVED2'); // Flow Status
            $table->enum('companyType', [1, 2])->comment('1 = Main Company, 2 = Outsourcing'); // Company Type
            $table->foreignId('position_id')->constrained()->onDelete('cascade'); // Relasi dengan Position
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flow_kyt_reports');
    }
};
