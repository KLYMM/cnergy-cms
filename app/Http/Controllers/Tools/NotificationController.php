<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Anouncement;
use App\Models\notificationIsRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index() {
        $anouncement = Anouncement::latest()->get();
        $markAsRead = notificationIsRead::where('user_id', Auth::user()->uuid)->get();
        return view('tools.notification.index', [
            'anouncement' => $anouncement,
            'markAsRead' => $markAsRead,
        ]);
    }
    
    public function store(Request $request) {
        $data = [
            'user_id' => Auth::user()->uuid,
            'anouncement_id' => $request->id,
            'is_read' => $request->is_read,
        ];
        notificationIsRead::create($data);
        return redirect()->route('notification');
    }
}