<?php

namespace KarabinSE\ContactForm\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactMessageSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * The data sent
     *
     * @var array
     */
    public $contactData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }
}
