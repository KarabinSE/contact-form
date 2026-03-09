<?php

namespace KarabinSE\ContactForm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use KarabinSE\ContactForm\Jobs\SendContactMessage;

class ContactMessageController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $attributes = $request->validate(config('contact-form.validation_rules'));

        SendContactMessage::dispatch(
            attributes: $attributes,
            recipients: config('contact-form.recipients'),
            bccRecipients: config('contact-form.bcc_recipients'),
            userAgent: $request->userAgent(),
        );

        return response()->json([
            'code' => 200,
            'message' => 'Message was sent',
        ]);
    }
}
