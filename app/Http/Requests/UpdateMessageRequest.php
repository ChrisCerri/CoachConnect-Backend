<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; 
use App\Models\Message; 

class UpdateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        if (!Auth::check()) {
            return false;
        }

        
        $message = $this->route('message');

        
        return $message && Auth::id() === $message->receiver_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'read_at' => 'nullable|date', // Permette di impostare una data o null (un-read)
        ];
    }
}