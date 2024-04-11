<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->char('sid', 8)->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('name',32);
            $table->string('slug',32)->unique();
            $table->timestamps();
            $table->unique(['user_id', 'name']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
