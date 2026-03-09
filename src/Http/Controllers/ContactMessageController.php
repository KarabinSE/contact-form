<?php

namespace KarabinSE\ContactForm\Http\Controllers;

use KarabinSE\ContactForm\Jobs\SendContactMessage;
use KarabinSE\ContactForm\Models\ContactFormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContactMessageController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $attributes = $request->validate(config('contact-form.validation_rules'));

        if (config('contact-form.log_to_database')) {
            ContactFormSubmission::create([
                'data' => [
                    'form' => $attributes,
                    'meta' => [
                        'user_agent' => $request->userAgent() ?? 'N/A'
                    ]
                ],
            ]);
        }

        SendContactMessage::dispatch(
            $attributes,
            config('contact-form.recipients'),
            config('contact-form.bcc_recipients')
        );

        return response()->json([
            'code' => 200,
            'message' => 'Message was sent',
        ]);
    }
}
