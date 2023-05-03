<?php

namespace App\Http\Controllers;

use App\Jobs\AnouncementEmailSending;
use App\Models\Anouncement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\AnouncementMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AnouncementMailController extends Controller
{
    public function index() {
        $job = new AnouncementEmailSending();

        $this->dispatch($job);
        return redirect()->route('anouncement.index')->with('status', 'Success Created Anouncement');
    }
}