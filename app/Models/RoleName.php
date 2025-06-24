<?php

namespace App\Models;

use Filament\Support\Contracts\HasLabel;

enum RoleName: string implements HasLabel
{
    case Administrator = 'Administrator';

    case Einsatzabteilung = 'Einsatzabteilung';

    case Vereinsmitglied = 'Vereinsmitglied';

    case Vereinsvorstand = 'Vereinsvorstand';

    case AltersUndEhrenabteilung = 'Alters- und Ehrenabteilung';

    case JugendfeuerwehrLeitung = 'Jugendfeuerwehr Leitung';

    case JugendfeuerwehrBetreuer = 'Jugendfeuerwehr Betreuer';

    case MinifeuerwehrLeitung = 'Minifeuerwehr Leitung';

    case MinifeuerwehrBetreuer = 'Minifeuerwehr Betreuer';

    case Gruppenfuehrer = 'Gruppenführer';

    case Wehrfuehrung = 'Wehrführung';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
