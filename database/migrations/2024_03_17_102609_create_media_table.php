<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->char('sid', 8)->unique();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('type');
            $table->tinyInteger('visibility')->default(0);//0 = hidden, 1 = public, 3 = restricted, 4 = paid
            $table->string('ext', 4);
            $table->boolean('has_versions');
            $table->integer('size_kb');
            $table->integer('duration_seconds')->nullable()->default(null);
            $table->integer('bitrate_kbs')->nullable()->default(null);
            $table->float('framerate')->nullable()->default(null);
            $table->integer('height')->nullable()->default(null);
            $table->integer('width')->nullable()->default(null);
            $table->boolean('has_audio')->nullable()->default(null);
            $table->string('original_name');
            $table->string('title', 32);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
