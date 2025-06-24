<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AusflugParticipantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'participants.*.name' => ['required'],
            'participants.*.street' => ['required'],
            'participants.*.zip_code' => ['required'],
            'participants.*.city' => ['required'],
            'participants.*.email' => ['nullable', 'email', 'max:254'],
            'participants.*.phone' => ['nullable'],
            'participants.*.type' => ['required', Rule::in(['ea', 'verein'])],
            'participants.*.primary' => ['boolean'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'participants.*.name.required' => 'Name wird benötigt',
            'participants.*.street.required' => 'Straße und Hausnummer wird benötigt',
            'participants.*.zipCode.required' => 'PLZ wird benötigt',
            'participants.*.city.required' => 'Stadt wird benötigt',
            'participants.*.type.required' => 'Angabe zu Vereinsmitgliedschaft wird benötigt',
            'participants.*.type.in' => 'Ungültige Angabe zur Vereinsmitgliedschaft',
            'participants.*.email.email' => 'E-Mail muss eine gültige E-Mail Adresse sein',
            'participants.*.email.max' => 'E-Mail Adresse ist zu lang',
        ];
    }
}
