<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailData['title'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->mailData['from']=='employee_info') {
            return new Content(
                view: 'emails.employee-info',
            );
        }else if ($this->mailData['from']=='welcome') {
            return new Content(
                view: 'emails.welcome',
            );
        }else if ($this->mailData['from']=='forgot-password') {
            return new Content(
                view: 'emails.forgot-password',
            );
        }else if($this->mailData['from']=='salary_increments'){
            return new Content(
                view: 'emails.email',
            );
        }else if($this->mailData['from']=='permanent'){
            return new Content(
                view: 'emails.permanent',
            );
        }else if($this->mailData['from']=='birthday'){
            return new Content(
                view: 'emails.birthday',
            );
        }else if($this->mailData['from']=='termination'){
            return new Content(
                view: 'emails.termination',
            );
        }else if($this->mailData['from']=='absent_email'){
            return new Content(
                view: 'emails.absent_days_email',
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
