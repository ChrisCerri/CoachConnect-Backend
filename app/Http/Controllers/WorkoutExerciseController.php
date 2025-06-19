<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateExerciseRequest; 
use App\Models\WorkoutExercise;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutExerciseController extends Controller
{
 
    public function index($planId)
    {
        $exercises = WorkoutExercise::where('workout_plan_id', $planId)->get();
        return response()->json($exercises);
    }


    public function show($planId, $exerciseId)
    {
        $exercise = WorkoutExercise::where('workout_plan_id', $planId)
            ->findOrFail($exerciseId);
        return response()->json($exercise);
    }

 
    public function store(Request $request, $planId)
    {
        $data = $request->validate([
            'exercise_name' => 'required|string|max:255',
            'sets' => 'nullable|integer',
            'reps' => 'nullable|integer',
            'rest_time' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $data['workout_plan_id'] = $planId;

        $exercise = WorkoutExercise::create($data);

        return response()->json($exercise, 201);
    }

  
    public function update(UpdateExerciseRequest $request, $planId, $exerciseId)
    {
        $exercise = WorkoutExercise::where('workout_plan_id', $planId)
            ->findOrFail($exerciseId);

        $data = $request->validated();

        $exercise->update($data);

        return response()->json($exercise);
    }


    public function destroy($planId, $exerciseId)
    {
        $exercise = WorkoutExercise::where('workout_plan_id', $planId)
            ->findOrFail($exerciseId);
        $exercise->delete();
        return response()->json(null, 204);
    }
}
