<?php

namespace App\Http\Requests;

use App\Http\Controllers\ContactForm\ContactTopics;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'topic' => ['required', Rule::enum(ContactTopics::class)],
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
