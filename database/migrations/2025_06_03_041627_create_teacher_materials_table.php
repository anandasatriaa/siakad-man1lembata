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
        Schema::create('teacher_materials', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign key ke users (guru)
            $table->unsignedBigInteger('teacher_id');

            // Foreign key optional ke kelas dan mata pelajaran
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Constraint foreign key
            $table->foreign('teacher_id')
                ->references('id')->on('teachers')
                ->onDelete('cascade');

            $table->foreign('class_id')
                ->references('id')->on('classes')
                ->onDelete('set null');

            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_materials');
    }
};
