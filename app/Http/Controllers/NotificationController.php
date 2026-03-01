<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        $url = url('/');
        if ($notification) {
            $notification->markAsRead();
            $url = $notification->data['url'] ?? url('/');
        }
        return redirect($url);
    }

    public function ajaxRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        $url = url('/');
        if ($notification) {
            $notification->markAsRead();
            $url = $notification->data['url'] ?? url('/');
        }
        $unreadCount = Auth::user()->unreadNotifications()->count();
        return response()->json(['url' => $url, 'unreadCount' => $unreadCount]);
    }
}
