<?php

namespace Tests\Feature;

use KarabinSE\ContactForm\Events\ContactMessageReceiptSent;
use KarabinSE\ContactForm\Events\ContactMessageSent;
use KarabinSE\ContactForm\Jobs\SendContactMessage;
use KarabinSE\ContactForm\Models\ContactFormSubmission;
use KarabinSE\ContactForm\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class ContactFormFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_send_a_contact_message()
    {
        // Arrange
        Mail::fake();
        Event::fake();
        Config::set('contact-form.send_receipt', true);
        $data = [
            'name' => 'Albin Nilsson',
            'email' => 'albin@karabin.se',
            'message' => 'Introducing the Saint Kitts and Nevis-inspired Salad, blending emotional style with local craftsmanship',
        ];

        // Act
        SendContactMessage::dispatchSync(
            $data,
            config('contact-form.recipients'),
            config('contact-form.bcc_recipients')
        );

        // Assert
        Mail::assertSent(config('contact-form.receipt_mailable'));
        Event::assertDispatched(ContactMessageSent::class);
        Event::assertDispatched(ContactMessageReceiptSent::class);
    }

    public function test_it_logs_submission_to_database_when_enabled()
    {
        Mail::fake();
        Config::set('contact-form.log_to_database', true);

        $this->postJson('/api/contact-message', [
            'name' => 'Albin Nilsson',
            'email' => 'albin@karabin.se',
            'message' => 'Test message for DB logging',
        ]);

        $this->assertDatabaseCount('contact_form_submissions', 1);
        $submission = ContactFormSubmission::first();
        $this->assertEquals('albin@karabin.se', $submission->data['form']['email']);
    }

    public function test_it_does_not_log_submission_to_database_when_disabled()
    {
        Mail::fake();
        Config::set('contact-form.log_to_database', false);

        $this->postJson('/api/contact-message', [
            'name' => 'Albin Nilsson',
            'email' => 'albin@karabin.se',
            'message' => 'Test message for DB logging',
        ]);

        $this->assertDatabaseCount('contact_form_submissions', 0);
    }
}
