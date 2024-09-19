<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ContactForm\ContactTopics;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'contactFormTopics' => ContactTopics::cases(),
        ]);
    }
}
