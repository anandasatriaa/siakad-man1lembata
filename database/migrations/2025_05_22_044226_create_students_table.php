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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nis', 20)->unique();
            $table->string('full_name', 100);
            $table->enum('gender', ['M', 'F']);
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->string('religion', 20);
            $table->year('enrollment_year');
            $table->unsignedBigInteger('class_id');
            $table->string('guardian_name', 100);
            $table->string('guardian_phone', 20);
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->unique()
                ->comment('link ke users.id, nullable karena akun belum dibuat');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
