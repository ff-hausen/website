<?php

namespace App\Models;

use Filament\Support\Contracts\HasLabel;

enum RoleName: string implements HasLabel
{
    case Administrator = 'Administrator';

    case Einsatzabteilung = 'Einsatzabteilung';

    case Vereinsmitglied = 'Vereinsmitglied';

    case AltersUndEhrenabteilung = 'Alters- und Ehrenabteilung';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
