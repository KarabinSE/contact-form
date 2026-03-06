<?php

namespace KarabinSE\ContactForm\Jobs;

use KarabinSE\ContactForm\Events\ContactMessageReceiptSent;
use KarabinSE\ContactForm\Events\ContactMessageSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Sent data
     *
     * @var array
     */
    public $attributes;

    /**
     * Recipients
     *
     * @var array
     */
    public $recipients;

    /**
     * BCC recipients
     *
     * @var array
     */
    public $bccRecipients;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $attributes, array $recipients, array $bccRecipients = [])
    {
        $this->attributes = $attributes;
        $this->recipients = $recipients;
        $this->bccRecipients = $bccRecipients;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailable = config('contact-form.mailable');

        Mail::to($this->recipients)
            ->bcc($this->bccRecipients)
            ->send(new $mailable($this->attributes));

        ContactMessageSent::dispatch($this->attributes);

        if (config('contact-form.send_receipt')) {
            $receiptMailable = config('contact-form.receipt_mailable');

            Mail::to($this->attributes['email'])
                ->send(new $receiptMailable($this->attributes));

            ContactMessageReceiptSent::dispatch($this->attributes);
        }

    }
}
