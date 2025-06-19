<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutPlanRequest extends FormRequest
{
    public function authorize()
    {
        
        return true;
    }

    public function rules()
{
    return [
        'title'       => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'exercises'   => ['nullable', 'array'],
        'exercises.*.id' => ['nullable', 'integer', 'exists:workout_exercises,id'],
        'exercises.*.exercise_name' => ['required', 'string', 'max:255'],
        'exercises.*.sets' => ['required', 'integer', 'min:1'],
        'exercises.*.reps' => ['required', 'integer', 'min:1'],
        'exercises.*.rest_time' => ['nullable', 'integer', 'min:0'],
        'exercises.*.notes' => ['nullable', 'string'],
    ];
}

}
