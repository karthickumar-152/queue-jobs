<?php

namespace App\Jobs;

use App\Mail\SendEmailTest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function handle()
    {
        //Send the email
        Mail::to($this->details['email'])->send(new SendEmailTest($this->details));

        //Push the data into Firebase
        $firebase = (new Factory)
            ->withServiceAccount(base_path($this->getFirebasePath()))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();

        $firebase->getReference('email_records')->push([
            'name'  => $this->details['name'] ?? 'Unknown',
            'email' => $this->details['email'],
            'title' => $this->details['title'] ?? '',
            'message' => $this->details['message'] ?? '',
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    private function getFirebasePath()
    {
        return env('FIREBASE_CREDENTIALS', 'storage/app/firebase/firebase_credentials.json');
    }
}
