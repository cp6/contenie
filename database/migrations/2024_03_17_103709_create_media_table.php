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
            $table->unsignedBigInteger('upload_id');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->tinyInteger('type');
            $table->tinyInteger('visibility')->default(0);//0 = hidden, 1 = public, 3 = restricted, 4 = paid
            $table->string('ext', 4);
            $table->unsignedBigInteger('directory_id');
            $table->integer('size_kb');
            $table->float('duration')->nullable()->default(null);
            $table->integer('bitrate_kbs')->nullable()->default(null);
            $table->float('framerate')->nullable()->default(null);
            $table->integer('height')->nullable()->default(null);
            $table->integer('width')->nullable()->default(null);
            $table->boolean('has_audio')->nullable()->default(null);
            $table->string('title', 32);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreign('directory_id')->references('id')->on('directories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
