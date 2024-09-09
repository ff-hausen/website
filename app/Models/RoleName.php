<?php

namespace App\Models;

enum RoleName: string
{
    case Administrator = 'Administrator';

    case Einsatzabteilung = 'Einsatzabteilung';

    case Vereinsmitglied = 'Vereinsmitglied';

    case AltersUndEhrenabteilung = 'Alters- und Ehrenabteilung';
}
