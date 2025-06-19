<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'exercise_name' => 'required|string|max:255',
            'sets' => 'required|integer|min:1',
            'reps' => 'required|integer|min:1',
            'rest_time' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'workout_plan_id' => 'required|exists:workout_plans,id',
        ];
    }
}
