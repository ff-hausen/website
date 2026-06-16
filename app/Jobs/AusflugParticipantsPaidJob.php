<?php

namespace App\Jobs;

use App\Mail\Ausflug\AnmeldungPaidMail;
use App\Models\AusflugParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class AusflugParticipantsPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Collection $participants) {}

    public function handle(): void
    {
        $submissions = $this->participants->groupBy->submission_id;

        foreach ($submissions as $submissionId => $participants) {

            $this->markSubmissionAsPaid($submissionId, $participants);

        }
    }

    public function markSubmissionAsPaid(string $submissionId, Collection $participants): void
    {

        $ids = $participants->pluck('id');
        AusflugParticipant::whereIn('id', $ids)->update([
            'paid_at' => now(),
        ]);

        $paidAmount = $participants->sum->price;
        $outstandingAmount = AusflugParticipant::whereSubmissionId($submissionId)->whereNull('paid_at')->get()->sum('price');

        $primary = AusflugParticipant::whereSubmissionId($submissionId)->wherePrimary(true)->first();

        Mail::to($primary->email)
            ->send(new AnmeldungPaidMail($submissionId, $paidAmount, $outstandingAmount));
    }
}
