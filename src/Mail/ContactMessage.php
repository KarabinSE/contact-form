<?php

namespace KarabinSE\ContactForm\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ContactMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $contactData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $random = Str::random(6);

        return $this
            ->replyTo($this->contactData['email'])
            ->subject((config('contact-form.include_app_name') ? config('app.name') . ' - ' : '') . config('contact-form.subject') . ' - #' . $random)
            ->text(config('contact-form.mail_view'));
    }
}
