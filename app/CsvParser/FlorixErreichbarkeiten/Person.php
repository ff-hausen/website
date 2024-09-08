<?php

namespace App\CsvParser\FlorixErreichbarkeiten;

use Spatie\LaravelData\Data;

class Person extends Data
{
    public function __construct(
        public ?string $lastName = null,
        public ?string $firstName = null,
        public ?int $staffNumber = null,
        public ?string $phonePrivate = null,
        public ?string $faxPrivate = null,
        public ?string $mobilePrivate = null,
        public ?string $emailPrivate = null,
        public ?string $street = null,
        public ?int $streetNumber = null,
        public ?string $zipCode = null,
        public ?string $city = null,
        public ?string $district = null,
        public ?string $phoneOffice = null,
        public ?string $faxOffice = null,
        public ?string $mobileOffice = null,
        public ?string $emailOffice = null,
    ) {}

    public function email(): ?string
    {
        $hostnameOffice = explode('@', $this->emailOffice, 2)[1] ?? null;

        if ($hostnameOffice === 'ff-frankfurt-hausen.de') {
            return $this->emailOffice;
        }

        return $this->emailPrivate ?? $this->emailOffice;
    }
}
