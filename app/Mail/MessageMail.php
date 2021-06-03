<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * int id pelamar
     * @return void
     */
    public function __construct($sender, $receiver, $subject, $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {		
        return $this->from($this->sender, get_website_name())->markdown('email/message')->subject($this->subject)->with([
			'sender' => $this->sender,
			'receiver' => $this->receiver,
			'subject' => $this->subject,
			'message' => $this->message,
        ]);
    }
}
