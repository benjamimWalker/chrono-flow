<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->date('date');
            $table->string('hours');
            $table->string('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('');
    }
};
