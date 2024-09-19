<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactFormRequest extends FormRequest
{
    public function rules(): array
    {
        $recipients = config('contact-form.recipients');

        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'topic' => ['required', Rule::in(array_keys($recipients))],
            'message' => ['required', 'min:10'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'E-Mail Adresse',
            'topic' => 'Thema',
            'message' => 'Nachricht',
        ];
    }
}
