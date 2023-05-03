<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Anouncement;
use Illuminate\Support\Str;
use App\Mail\AnouncementMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AnouncementEmailSending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $anouncement = Anouncement::latest()->first();

        $data = Str::of($anouncement->targetRole)->explode(',');
        foreach ($data as $d) {
            $user = User::where('role_id', $d)->get();
            foreach ($user as $u) {
                Mail::to($u->email)->send(new AnouncementMail($anouncement));
            }
        }
    }
}