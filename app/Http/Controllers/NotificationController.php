<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller für Benachrichtigungen
 */
class NotificationController extends Controller
{
    /**
     * Alle Benachrichtigungen des aktuellen Benutzers abrufen
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = $user->unreadNotifications()->count();


        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Anzahl der ungelesenen Benachrichtigungen abrufen
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Eine spezifische Benachrichtigung als gelesen markieren
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Benachrichtigung nicht gefunden'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Benachrichtigung als gelesen markiert'
        ]);
    }

    /**
     * Alle Benachrichtigungen als gelesen markieren
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Alle Benachrichtigungen als gelesen markiert'
        ]);
    }

    /**
     * Eine Benachrichtigung löschen
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Benachrichtigung nicht gefunden'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Benachrichtigung gelöscht'
        ]);
    }
}
