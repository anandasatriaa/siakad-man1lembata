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
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip', 20)->unique();
            $table->string('full_name', 100);
            $table->enum('gender', ['F', 'M']);
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->string('religion', 20);
            $table->year('enrollment_year');
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->unique()->comment('link ke users.id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('class_id')
                ->references('id')->on('classes')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
