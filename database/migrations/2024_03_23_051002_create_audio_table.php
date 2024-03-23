<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->tinyInteger('index');
            $table->string('codec');
            $table->string('profile');
            $table->integer('rate');
            $table->tinyInteger('channels');
            $table->string('layout');
            $table->integer('size_kb');
            $table->float('duration')->nullable()->default(null);
            $table->integer('bitrate_kbs')->nullable()->default(null);
            $table->string('timebase');
            $table->string('name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audio');
    }
};
