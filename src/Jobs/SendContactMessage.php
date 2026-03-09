<?php

namespace KarabinSE\ContactForm\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use KarabinSE\ContactForm\Events\ContactMessageReceiptSent;
use KarabinSE\ContactForm\Events\ContactMessageSent;
use KarabinSE\ContactForm\Models\ContactFormSubmission;

class SendContactMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public array $attributes,
        public array $recipients,
        public array $bccRecipients = [],
        public string $userAgent = '',
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailable = config('contact-form.mailable');
        /** @var class-string<\Illuminate\Mail\Mailable> $mailable */
        if (config('contact-form.log_submissions')) {
            $this->logContactFormSubmission();
        }

        Mail::to($this->recipients)
            ->bcc($this->bccRecipients)
            ->send(new $mailable($this->attributes));

        ContactMessageSent::dispatch($this->attributes);

        if (config('contact-form.send_receipt')) {
            /** @var class-string<\Illuminate\Mail\Mailable> $receiptMailable */
            $receiptMailable = config('contact-form.receipt_mailable');

            Mail::to($this->attributes['email'])
                ->send(new $receiptMailable($this->attributes));

            ContactMessageReceiptSent::dispatch($this->attributes);
        }

    }

    protected function logContactFormSubmission()
    {
        $submissionData = ContactFormSubmission::make([
            'data' => [
                'form' => $this->attributes,
                'meta' => [
                    'user_agent' => $this->userAgent ?? 'N/A',
                ],
            ],
        ]);
        if (config('contact-form.log_driver') === 'database') {
            $submissionData->save();
        } else {
            Storage::put(config('contact-form.log_file_location').'/'.$submissionData->filename, $submissionData->toJson());
        }
    }
}
