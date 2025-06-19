<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = Auth::user()->notifications()->get();
        return response()->json($notifications);
    }


    public function store(StoreNotificationRequest $request) 
    {
        $notification = Notification::create($request->validated());

        return response()->json([
            'message' => 'Notification created successfully',
            'notification' => $notification
        ], 201);
    }


    public function show(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return response()->json($notification);
    }


    public function update(UpdateNotificationRequest $request, Notification $notification) // Usa UpdateNotificationRequest
    {
        $notification->update($request->validated());

        return response()->json([
            'message' => 'Notification updated successfully',
            'notification' => $notification
        ]);
    }


    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully'], 204);
    }
}