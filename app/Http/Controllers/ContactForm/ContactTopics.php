<?php

namespace App\Http\Controllers\ContactForm;

enum ContactTopics: string
{
    case Einsatzabteilung = 'Einsatzabteilung';

    case Jugendfeuerwehr = 'Jugendfeuerwehr';

    case Minifeuerwehr = 'Minifeuerwehr';

    case Sonstiges = 'Sonstiges';
}
