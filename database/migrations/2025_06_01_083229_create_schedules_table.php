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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('day'); // Senin, Selasa, dst
            $table->string('start_time'); // format HH:MM
            $table->string('end_time');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null'); // Bisa null untuk istirahat
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null'); // Bisa null untuk istirahat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
