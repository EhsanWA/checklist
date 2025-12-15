<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReportSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Report $report;
    public string $pdfPath;

    public function __construct(Report $report, string $pdfPath)
    {
        $this->report = $report;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this
            ->subject('Nieuwe rapportage ingediend')
            ->view('emails.report-submitted')
            ->attach(
                Storage::disk('public')->path($this->pdfPath),
                [
                    'as' => 'rapportage-' . $this->report->id . '.pdf',
                    'mime' => 'application/pdf',
                ]
            );
    }
}
