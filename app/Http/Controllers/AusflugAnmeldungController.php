<?php

namespace App\Http\Controllers;

use App\Http\Requests\AusflugParticipantRequest;
use App\Mail\AusflugAnmeldungVerificationMail;
use App\Models\AusflugParticipant;
use Illuminate\Support\Facades\Mail;
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

        $primary = null;
        foreach ($request->validated('participants') as $participant) {
            $newParticipant = AusflugParticipant::create([
                ...$participant,
                'submission_id' => $submissionId,
            ]);

            if ($newParticipant->primary) {
                $primary = $newParticipant;
            }
        }

        // Send email with confirmation link
        Mail::to($primary->email)
            ->send(new AusflugAnmeldungVerificationMail($submissionId));

    }

    public function verification(string $submissionId)
    {
        $affectedRows = AusflugParticipant::whereSubmissionId($submissionId)
            ->whereVerified(false)
            ->update([
                'verified' => true,
            ]);

        $participants = AusflugParticipant::whereSubmissionId($submissionId)->get();

        if ($affectedRows > 0) {
            // TODO: Send confirmation email to all participants

            // TODO: Send notification to board
        }

        return Inertia::render('Vereinsausflug/Confirmation', [
            'participants' => $participants,
        ]);
    }
}
