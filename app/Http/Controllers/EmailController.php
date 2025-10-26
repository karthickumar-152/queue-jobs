<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\EmailRecord;

class EmailController extends Controller
{
    public function emailTest()
    {
        $name = 'Karthic';
        $baseEmail = 'karthickumarerplm@gmail.com';
        $email = $baseEmail;

        // Get all existing emails starting with baseEmail
        $existingEmails = EmailRecord::where('email', 'like', str_replace('@', '%@', $baseEmail))
            ->pluck('email')->toArray();

        // Find next available increment
        if (in_array($baseEmail, $existingEmails)) {
            $i = 1;
            do {
                $email = preg_replace('/\+\d+@/', '+' . $i . '@', $baseEmail); // generate +N
                $email = str_contains($email, '+') ? $email : str_replace('@', '+' . $i . '@', $baseEmail);
                $i++;
            } while (in_array($email, $existingEmails));
        }

        // Save record
        EmailRecord::create([
            'name'  => $name,
            'email' => $email,
        ]);

        // Dispatch job (mail + Firebase)
        $details = [
            'name'    => $name,
            'email'   => $email,
            'title'   => 'Queue Test Email',
            'message' => 'Mail sent successfully!',
        ];
        SendEmailJob::dispatch($details);

        return response()->json([
            'message' => 'Email saved & mail queued!',
            'email'   => $email
        ]);
    }
}
