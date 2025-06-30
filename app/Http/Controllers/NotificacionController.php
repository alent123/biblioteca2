<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificacionController extends Controller
{
    public function markAsRead($id): JsonResponse
    {
        $notification = Notificacion::where('id', $id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
        }

        $notification->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notificacion::where('usuario_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $notifications = auth()->user()->notificaciones()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.notifications', compact('notifications'));
    }

    public function destroy($id): JsonResponse
    {
        $notification = Notificacion::where('id', $id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
} 