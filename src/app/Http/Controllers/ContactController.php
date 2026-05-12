<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(string $locale)
    {
        return view('contact');
    }

    public function send(string $locale, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Mail::to(config('mail.from.address'))->send(new ContactFormMail(
            senderName: $validated['name'],
            senderEmail: $validated['email'],
            senderSubject: $validated['subject'],
            body: $validated['message'],
        ));

        return back()->with('success', __('Message sent successfully.'));
    }
}
