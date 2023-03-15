<?php

namespace App\View\Components;

use App\Models\Anouncement;
use App\Models\notificationIsRead;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $anouncement = Anouncement::latest()->get();
        $notif = notificationIsRead::where('user_id', Auth::user()->uuid)->get();
        $notifIsRead = 0;
        $notifUser = 0;
        foreach ($notif as  $n) {
            if($n->is_read == 1) {
                $notifIsRead++;
            }
        }
        foreach($anouncement as $an) {
            $data = Str::of($an->targetRole)->explode(',');
            foreach ($data as $d) {
                if ($d == Auth::user()->role_id) {
                    $notifUser++;
                }
            }
        }

        return view('components.navbar', [
            'anouncement' => $anouncement,
            'count' => $notifUser - $notifIsRead,
        ]);
    }
}