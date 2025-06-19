<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\TrainingPlan;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;

class ExerciseController extends Controller
{

    public function index(TrainingPlan $trainingPlan)
    {
        if ($trainingPlan->personal_trainer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return response()->json($trainingPlan->exercises);
    }


    public function store(StoreExerciseRequest $request, TrainingPlan $trainingPlan) // Usa StoreExerciseRequest
    {
        $exercise = $trainingPlan->exercises()->create($request->validated());

        return response()->json([
            'message' => 'Exercise created successfully',
            'exercise' => $exercise
        ], 201);
    }

  

    public function show(TrainingPlan $trainingPlan, Exercise $exercise)
    {
        if ($exercise->training_plan_id !== $trainingPlan->id || $trainingPlan->personal_trainer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return response()->json($exercise);
    }


    public function update(UpdateExerciseRequest $request, TrainingPlan $trainingPlan, Exercise $exercise) // Usa UpdateExerciseRequest
    {
        $exercise->update($request->validated());

        return response()->json([
            'message' => 'Exercise updated successfully',
            'exercise' => $exercise
        ]);
    }

    public function indexForClient(TrainingPlan $trainingPlan)
    {

        if (Auth::user()->role !== 'client') {
            abort(403, 'Unauthorized action.');
        }


        if ($trainingPlan->client_id !== Auth::id()) {
            abort(403, 'Unauthorized action: This training plan does not belong to you.');
        }

        return response()->json($trainingPlan->exercises);
    }


    public function destroy(TrainingPlan $trainingPlan, Exercise $exercise)
    {
        if ($exercise->training_plan_id !== $trainingPlan->id || $trainingPlan->personal_trainer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $exercise->delete();

        return response()->json(['message' => 'Exercise deleted successfully'], 204);
    }
}