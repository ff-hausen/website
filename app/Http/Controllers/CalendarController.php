<?php

namespace App\Http\Controllers;

use App\Http\Resources\Calendar\EventResource;
use App\Models\Calendar\Department;
use App\Models\Calendar\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        Validator::make($request->query(), [
            'department' => ['required', Rule::enum(Department::class)],
        ])->validate();

        return EventResource::collection(
            Event::upcoming()->whereDepartment($request->query('department'))->get()
        );
    }

    public function store(Request $request) {}

    public function show(Event $event) {}

    public function update(Request $request, Event $event) {}

    public function destroy(Event $event) {}
}
