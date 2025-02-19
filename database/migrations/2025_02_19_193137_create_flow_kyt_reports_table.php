<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flow_kyt_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('flow'); // 1: CHECKED, 2: REVIEWED, 3: APPROVED1, 4: APPROVED2
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flow_kyt_reports');
    }
};
