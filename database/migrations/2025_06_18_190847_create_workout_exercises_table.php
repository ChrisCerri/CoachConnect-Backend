<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutExercisesTable extends Migration
{
    public function up()
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workout_plan_id');  
            $table->string('exercise_name');
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->string('rest_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('workout_plan_id')->references('id')->on('workout_plans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('workout_exercises');
    }
}
