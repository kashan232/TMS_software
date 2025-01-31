<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Email ke liye data variable

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->data['subject']) // Email ka subject
                    ->view('admin_panel.customer.customer_mail') // Email ka view
                    ->with('data', $this->data); // Data pass karna
    }
}