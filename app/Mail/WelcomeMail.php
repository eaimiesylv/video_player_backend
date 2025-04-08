<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use App\Services\EmailDataService;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $emailData = [];
    public $template;

    public function __construct($emailType, $otherData = [])
    {


        switch ($emailType) {
            case 'receipt':
            case 'invoice':
                // \Log::info('Email Data Type:', [gettype($otherData)]);
                // \Log::info('Email Data:', $otherData);
                $this->emailData = $otherData['user_data'];
                break;

            case 'onboarding':
                $this->emailData = EmailDataService::getEmailData($emailType, $otherData);
                break;

            default:
                $this->emailData = EmailDataService::getEmailData($emailType, $otherData);
                break;
        }

        // Dynamically set the template based on the emailType
        $this->template = $this->getTemplateByType($emailType);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Go Dash Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->template, // Dynamically use the selected template
            with: ['emailData' => $this->emailData] // Pass data to the template
        );
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

    /**
     * Determine the template based on the email type.
     */
    private function getTemplateByType($emailType = "onboarding")
    {
        switch ($emailType) {
            case 'receipt':
            case 'invoice':  // Grouping both 'receipt' and 'invoice'
                return 'mail.receiptEmail';
            case 'onboarding':
                return 'mail.onboardingEmail';
            default:
                return 'mail.onboardingEmail';
        }

    }
}
