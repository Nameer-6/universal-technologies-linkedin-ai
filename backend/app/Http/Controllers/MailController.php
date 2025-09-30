<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Validate input from the contact form.
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        // Send the email to the designated address.
        Mail::to(config('mail.from.address'))->send(new ContactFormMail($validatedData));

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully.',
        ]);
    }
}
