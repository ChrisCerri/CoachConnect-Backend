<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTrainingPlanRequest;
use App\Models\Client;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkoutPlanController extends Controller
{

    public function index($clientId)
    {
        $plans = WorkoutPlan::with('exercises')->where('client_id', $clientId)->get();
        return response()->json($plans);
    }


    public function indexAll()
    {
        $plans = WorkoutPlan::with('exercises')->get();
        return response()->json($plans);
    }


    public function show($clientId, $planId)
    {
        $plan = WorkoutPlan::with('exercises')
            ->where('client_id', $clientId)
            ->findOrFail($planId);

        return response()->json($plan);
    }


public function store(Request $request, $clientId)
{
    Log::info('WorkoutPlanController@store called', [
        'clientId' => $clientId,
        'request_data' => $request->all(),
    ]);

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'exercises' => 'sometimes|array',
        'exercises.*.exercise_name' => 'required|string|max:255',
        'exercises.*.sets' => 'nullable|integer|min:0',
        'exercises.*.reps' => 'nullable|integer|min:0',
        'exercises.*.rest_time' => 'nullable|string|max:100',
        'exercises.*.notes' => 'nullable|string',
    ]);

    $data['client_id'] = (int) $clientId;

    DB::beginTransaction();

    try {
        Log::info('Creating WorkoutPlan...');
        $plan = WorkoutPlan::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'client_id' => $data['client_id'],
        ]);
        Log::info("WorkoutPlan created with ID {$plan->id}");

        if (!empty($data['exercises'])) {
            Log::info('Creating exercises...');
            $plan->exercises()->createMany($data['exercises']);
            Log::info('Exercises created.');
        }

        DB::commit();

        return response()->json($plan->load('exercises'), 201);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Errore durante creazione piano ed esercizi: ' . $e->getMessage());
        return response()->json([
            'message' => 'Errore durante il salvataggio del piano e degli esercizi',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function update(UpdateTrainingPlanRequest $request, $clientId, $planId)
    {
        Log::info('WorkoutPlanController@update called', [
            'clientId' => $clientId,
            'planId' => $planId,
            'request_data' => $request->all(),
        ]);

        $plan = WorkoutPlan::where('client_id', $clientId)->findOrFail($planId);

        $data = $request->validated();
        $data['client_id'] = (int) $clientId;

        Log::info('Validated data', $data);


        $plan->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'client_id' => $clientId,
        ]);

        $incomingExercises = $data['exercises'] ?? [];

        Log::info('Incoming exercises', $incomingExercises);

        $incomingIds = collect($incomingExercises)->pluck('id')->filter()->all();

        Log::info('IDs of exercises to keep/update', $incomingIds);


        $deleted = $plan->exercises()->whereNotIn('id', $incomingIds)->delete();
        Log::info("Deleted $deleted exercises");


        foreach ($incomingExercises as $exerciseData) {
            if (!empty($exerciseData['id'])) {
                $exercise = $plan->exercises()->find($exerciseData['id']);
                if ($exercise) {
                    $exercise->update($exerciseData);
                    Log::info("Updated exercise ID {$exerciseData['id']}");
                }
            } else {
                $newExercise = $plan->exercises()->create($exerciseData);
                Log::info("Created new exercise ID {$newExercise->id}");
            }
        }

        $plan->load('exercises');

        Log::info('Update complete, returning plan', ['plan' => $plan]);

        return response()->json([
            'message' => 'Workout plan and exercises updated successfully',
            'data' => $plan,
        ]);
    }


    public function updateByEmail(UpdateTrainingPlanRequest $request, $email, $planId)
    {
        $client = Client::where('email', $email)->first();

        if (!$client) {
            return response()->json(['message' => 'Cliente non trovato'], 404);
        }

        $plan = WorkoutPlan::where('client_id', $client->id)->findOrFail($planId);

        $data = $request->validated();
        $data['client_id'] = $client->id;

        $plan->update($data);
        return response()->json($plan);
    }


    public function destroy($clientId, $planId)
    {
        $plan = WorkoutPlan::where('client_id', $clientId)->findOrFail($planId);
        $plan->delete();
        return response()->json(null, 204);
    }
}
