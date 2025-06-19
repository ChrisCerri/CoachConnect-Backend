<?php 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('DEBUG AuthController - Inizio tentativo di registrazione.');
        Log::info('DEBUG AuthController - Dati request all(): ' . json_encode($request->all()));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:trainer,client'],
        ]);

        Log::info('DEBUG AuthController - Dati validati: ' . json_encode($validated));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Registrazione riuscita. Utente loggato: ' . $user->email);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registrazione riuscita!'
        ], 201);
    }

    public function login(Request $request)
    {
        Log::info('DEBUG AuthController - Inizio login');

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('DEBUG AuthController - Credenziali non valide per: ' . $credentials['email']);
            return response()->json(['message' => 'Credenziali non valide'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('DEBUG AuthController - Login riuscito per: ' . $user->email);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout effettuato con successo']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
