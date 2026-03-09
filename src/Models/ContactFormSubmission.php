<?php

namespace KarabinSE\ContactForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContactFormSubmission extends Model
{
    protected $guarded = [];

    protected $casts = ['data' => 'array'];

    public function getTable(): string
    {
        return config('contact-form.database_table', 'contact_form_submissions');
    }

    public function getFilenameAttribute(): string
    {
        return now()->unix().'-'.hash('adler32', $this->attributes['data']).'.json';
    }

    public function logToFile()
    {
        Storage::put(config('contact-form.log_file_location').'/'.$this->filename, $this->toJson());
    }
}
