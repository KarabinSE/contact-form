<?php

namespace KarabinSE\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KarabinSE\ContactForm\ContactForm
 */
class ContactForm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'contact-form';
    }
}
