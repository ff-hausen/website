<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $contactFormRecipients = config('contact-form.recipients');

        return Inertia::render('Welcome', [
            'contactFormTopics' => array_keys($contactFormRecipients),
        ]);
    }
}
