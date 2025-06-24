<?php

namespace App\Http\Controllers;

use App\Http\Requests\AusflugParticipantRequest;
use App\Models\AusflugParticipant;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AusflugAnmeldungController extends Controller
{
    public function index()
    {
        return Inertia::render('Vereinsausflug/Anmeldung');
    }

    public function store(AusflugParticipantRequest $request)
    {
        $submissionId = Str::uuid7();

        foreach ($request->validated('participants') as $participant) {
            AusflugParticipant::create([
                ...$participant,
                'submission_id' => $submissionId,
            ]);
        }

    }
}
