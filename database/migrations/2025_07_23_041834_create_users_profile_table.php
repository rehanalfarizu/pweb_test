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
        Schema::create('users_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fullname')->nullable();
            $table->string('username')->unique()->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->json('hobbies')->nullable();
            $table->string('avatar')->nullable();
            $table->json('badges')->nullable();
            $table->integer('level')->default(1);
            $table->integer('progress')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_profile');
    }
};

