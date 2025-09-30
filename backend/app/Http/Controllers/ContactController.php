<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactRequestMail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // ✔ validate
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'linkedinProfile' => 'nullable|url|max:255',
            'phone'     => 'required|string|max:30',
            'selectedPackage' => 'nullable|string|max:100',
        ]);

        // ✔ send e‑mail (to yourself, a team mailbox, etc.)

        Mail::to(config('mail.contact_to', env('ADMIN_EMAIL')))
        ->send(new ContactRequestMail($data));
   
 
        // ✔ JSON response for the frontend
        return response()->json(['message' => 'Contact request received.'], 201);
    }
}