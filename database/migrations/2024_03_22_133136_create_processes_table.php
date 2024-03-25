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
            $table->char('media_sid', 8);
            $table->string('command');
            $table->tinyInteger('type');
            $table->timestamps();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('media_sid')->references('sid')->on('media')->onDelete('cascade');
            $table->unique(['media_id', 'command']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
