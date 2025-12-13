<?php

namespace App\Services;

use App\Mail\ReportSubmitted;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;

class ReportSubmissionService
{
    public function sendReport(Report $report, string $pdfPath): void
    {
        Mail::to(config('mail.report_recipient'))
            ->send(new ReportSubmitted($report, $pdfPath));
    }
}
