<?php

namespace App\Models;

enum RoleName: string
{
    case Einsatzabteilung = 'Einsatzabteilung';

    case Vereinsmitglied = 'Vereinsmitglied';

    case AltersUndEhrenabteilung = 'Alters- und Ehrenabteilung';
}
