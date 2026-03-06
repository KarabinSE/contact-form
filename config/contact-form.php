<?php

// config for KarabinSE/ContactForm
return [
    'recipients' => [
        'example@example.com',
    ],

    'bcc_recipients' => [],

    'include_app_name' => true,

    'subject' => 'Nytt meddelande från hemsidan',

    'mail_view' => 'contact-form::emails.contact_plain',
    'mailable' => \KarabinSE\ContactForm\Mail\ContactMessage::class,
    'mail_view' => 'contact-form::emails.contact_plain',

    'send_receipt' => false,
    'receipt_subject' => 'Tack för ditt meddelande',
    'receipt_mailable' => \KarabinSE\ContactForm\Mail\ReceiptMessage::class,
    'receipt_mail_markdown_view' => 'contact-form::emails.receipt',

    'form_request_class' => \KarabinSE\ContactForm\Http\Requests\ContactMessageRequest::class,

    'validation_rules' => [
        'name' => 'required|max:125',
        'email' => 'required|max:125',
        'phone' => 'max:30',
        'message' => 'required|max:3000',
    ],
];
