<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('media_id');
            $table->char('media_sid', 8)->unique();
            $table->string('size_1')->default(null)->nullable();//Main
            $table->integer('bitrate_1')->default(null)->nullable();//Kbps
            $table->string('size_2')->default(null)->nullable();
            $table->integer('bitrate_2')->default(null)->nullable();
            $table->string('size_3')->default(null)->nullable();
            $table->integer('bitrate_3')->default(null)->nullable();
            $table->string('size_4')->default(null)->nullable();//Lowest
            $table->integer('bitrate_4')->default(null)->nullable();
            $table->timestamps();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('media_sid')->references('sid')->on('media')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
