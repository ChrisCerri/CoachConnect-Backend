<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
public function index(Request $request)
{
    $user = $request->user();

    if ($user->role === 'client') {
        $users = User::where('role', 'trainer')->select('id', 'name')->get();
    } else if ($user->role === 'trainer') {
        $users = User::where('id', '<>', $user->id)->select('id', 'name')->get();
    } else {
        $users = collect();
    }

    return response()->json($users);
}

}
