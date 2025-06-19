<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException; 

class StoreMessageRequest extends FormRequest
{

    public function authorize(): bool
    {
        
        if (!Auth::check()) {
            return false;
        }

        
        if ($this->has('receiver_id') && $this->input('receiver_id') === Auth::id()) {
           
            throw ValidationException::withMessages([
                'receiver_id' => ['You cannot send messages to yourself.'],
            ]);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ];
    }
}
