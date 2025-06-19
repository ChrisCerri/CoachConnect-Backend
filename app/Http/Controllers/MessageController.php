<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        $messages = Message::where('receiver_id', $userId)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }


    public function sent()
    {
        $userId = Auth::id();

        $messages = Message::where('sender_id', $userId)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }


    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|not_in:' . Auth::id(),
            'message_content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message_content' => $request->message_content,
        ]);

        $message->load('sender:id,name', 'receiver:id,name');

        return response()->json($message, 201);
    }


    public function markAsRead($id)
    {
        $userId = Auth::id();

        $message = Message::where('id', $id)
            ->where('receiver_id', $userId)
            ->firstOrFail();

        $message->read_at = now();
        $message->save();

        return response()->json(['success' => true]);
    }

    public function unreadCountBySender()
    {
        $userId = Auth::id();

        $counts = Message::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        return response()->json($counts);
    }
}
