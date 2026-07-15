<?php

namespace App\Mail;

use App\Models\WeeklyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WeeklyReport $report)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weekly Bid Report - '.$this->report->week_start->format('d M').' to '.$this->report->week_end->format('d M Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-report',
        );
    }
}
