<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; 
use App\Models\Client; 

/**
 * Gestisce la validazione e l'autorizzazione per l'aggiornamento di un cliente esistente.
 */
class UpdateClientRequest extends FormRequest
{
    /**
     * Determina se l'utente è autorizzato a fare questa richiesta.
     *
     * @return bool
     */
    public function authorize(): bool
    {
       
        $client = $this->route('client');

        return Auth::check() && $client && $client->personal_trainer_id === Auth::id();
    }

    /**
     * Ottiene le regole di validazione che si applicano alla richiesta.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        $client = $this->route('client');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ];
    }
}
