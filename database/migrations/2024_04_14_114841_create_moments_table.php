<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moments', function (Blueprint $table) {
            $table->id();
            $table->char('sid',8)->unique();
            $table->unsignedBigInteger('media_id');
            $table->string('name', 32);
            $table->integer('seconds');
            $table->timestamps();
            $table->unique(['media_id', 'seconds']);
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moments');
    }
};
