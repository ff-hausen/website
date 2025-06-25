<?php

namespace App\Http\Controllers;

use App\Http\Requests\AusflugParticipantRequest;
use App\Mail\AusflugAnmeldungConfirmationMail;
use App\Mail\AusflugAnmeldungInfoMail;
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
            // Send confirmation email to all participants
            $participants->whereNotNull('email')->each(fn ($p) => Mail::to($p->email)->send(new AusflugAnmeldungConfirmationMail($participants)));

            // Send notification to board
            $infoRecipients = explode(',', config('verein-ausflug.info_recipients'));
            Mail::to($infoRecipients)->send(new AusflugAnmeldungInfoMail($participants));
        }

        return Inertia::render('Vereinsausflug/Confirmation', [
            'participants' => $participants,
        ]);
    }
}
