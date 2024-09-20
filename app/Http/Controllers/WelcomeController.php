<?php

namespace App\Http\Controllers;

use App\Models\ContactFormTopics;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $contactFormRecipients = config('contact-form.recipients');

        return Inertia::render('Welcome', [
            'contactFormTopics' => ContactFormTopics::getTopics(),
        ]);
    }
}
