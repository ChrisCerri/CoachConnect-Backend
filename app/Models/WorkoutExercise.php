<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'exercise_name',
        'sets',
        'reps',
        'rest_time',
        'notes',
    ];

    // Relazione: un esercizio appartiene a un piano di allenamento
    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}
