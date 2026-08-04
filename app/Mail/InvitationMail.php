<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Invitation to join a group, sent synchronously (deliberately not
 * ShouldQueue) so console commands and controllers get immediate
 * delivery feedback.
 */
class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You're invited to join {$this->invitation->group->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.invitation',
            with: [
                'groupName'   => $this->invitation->group->name,
                'inviterName' => $this->invitation->inviter?->name,
                'acceptUrl'   => route('invitation.show', $this->invitation->token),
                'expiresAt'   => $this->invitation->expires_at->toFormattedDateString(),
            ],
        );
    }
}
