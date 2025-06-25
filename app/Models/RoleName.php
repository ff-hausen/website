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

    case JugendfeuerwehrLeitung = 'JF Leitung';

    case JugendfeuerwehrBetreuer = 'JF Betreuer';

    case MinifeuerwehrLeitung = 'MF Leitung';

    case MinifeuerwehrBetreuer = 'MF Betreuer';

    case Gruppenfuehrer = 'Gruppenführer';

    case Wehrfuehrung = 'Wehrführung';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
