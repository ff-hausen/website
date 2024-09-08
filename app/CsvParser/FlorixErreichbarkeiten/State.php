<?php

namespace App\CsvParser\FlorixErreichbarkeiten;

enum State: string
{
    case Start = 'start';

    case Header = 'header';

    case Person1 = 'person1';

    case Person2 = 'person2';

    case Finish = 'finish';
}
