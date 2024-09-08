<?php

namespace App\CsvParser\FlorixErreichbarkeiten;

use Illuminate\Support\Collection;
use Tii\CsvStateParser\CsvStateParser;

/**
 * @extends CsvStateParser<Collection<int, \App\CsvParser\FlorixErreichbarkeiten\Person>>
 */
class Parser extends CsvStateParser
{
    /**
     * @var Collection<int, Person>
     */
    protected Collection $people;

    protected ?Person $person = null;

    public function __construct()
    {
        parent::__construct();
        $this->people = new Collection;
    }

    protected function mapValue(string $value): string
    {
        return mb_convert_encoding($value, 'utf-8', 'latin1');
    }

    /**
     * @return Collection<int, Person>
     */
    public function result(): Collection
    {
        return $this->people;
    }

    protected function stateStart(array $row): void
    {
        if ($row[0] === 'Ausgewählt') {
            $this->state(State::Header);
        }
    }

    protected function stateHeader(array $row): void
    {
        $this->skip(1)->state(State::Person1);
    }

    protected function statePerson1(array $row): void
    {
        if (str_starts_with($row[0], 'Bearbeiter: ')) {
            $this->done();
        }

        $this->person = new Person(
            lastName: $row[0] ?: null,
            firstName: $row[4] ?: null,
            staffNumber: (int) $row[6] ?: null,
            phonePrivate: $row[8] ?: null,
            faxPrivate: $row[10] ?: null,
            mobilePrivate: $row[11] ?: null,
            emailPrivate: $row[12] ?: null,
        );

        $this->state(State::Person2);
    }

    protected function statePerson2(array $row): void
    {
        $this->person->street = $row[0] ?: null;
        $this->person->streetNumber = (int) $row[2] ?: null;
        $this->person->zipCode = $row[4] ?: null;
        $this->person->city = $row[5] ?: null;
        $this->person->district = $row[6] ?: null;
        $this->person->phoneOffice = $row[8] ?: null;
        $this->person->faxOffice = $row[10] ?: null;
        $this->person->mobileOffice = $row[11] ?: null;
        $this->person->emailOffice = $row[12] ?: null;

        $this->people->add($this->person);
        $this->person = null;

        $this->state(State::Person1);
    }
}
