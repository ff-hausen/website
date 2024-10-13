<?php

namespace App\Http\Controllers;

use App\Models\Calendar\Event;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'events' => Event::upcoming()->whereDepartment('ea')
                ->with('responsible:full_name')
                ->get(),
        ]);
    }
}
