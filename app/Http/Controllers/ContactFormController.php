<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactMessage;
use App\Models\ContactFormTopics;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function store(ContactFormRequest $request)
    {
        $contactForm = ContactFormTopics::whereName($request['topic'])->first();

        Mail::to($contactForm->to)
            ->cc($contactForm->cc)
            ->send(new ContactMessage(
                ...$request->only(['name', 'email', 'message']
                )));

    }
}
