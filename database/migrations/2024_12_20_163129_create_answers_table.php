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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->string('gender');
            $table->string('age');
            $table->string('current_situation');
            $table->string('prac_sports');
            $table->string('anxious_week');
            $table->string('feel_anxious');
            $table->string('treating_anxiety');
            $table->string('act_in_situations');
            $table->string('describe_mood');
            $table->string('content _health_support');
            $table->string('apps_daily');
            $table->string('social_situations');
            $table->string('source_anxiety');
            $table->string('anxiety_match');
            $table->string('interacting_with_people');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
