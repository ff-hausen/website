<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactMessage;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function store(ContactFormRequest $request)
    {
        $recipients = config('contact-form.recipients');

        $to = collect($recipients[$request['topic']]['to'])
            ->filter()
            ->map(fn ($address) => new Address($address));

        $cc = collect($recipients[$request['topic']]['cc'])
            ->filter()
            ->map(fn ($address) => new Address($address));

        Mail::to($to)
            ->cc($cc)
            ->send(new ContactMessage(...$request->only(['name', 'email', 'message'])));

    }
}
