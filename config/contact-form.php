<?php

return [

    'recipients' => [
        'Einsatzabteilung' => [
            'to' => explode(',', env('CONTACT_EA_TO')),
            'cc' => explode(',', env('CONTACT_EA_CC')),
        ],

        'Jugendfeuerwehr' => [
            'to' => explode(',', env('CONTACT_JF_TO')),
            'cc' => explode(',', env('CONTACT_JF_CC')),
        ],

        'Minifeuerwehr' => [
            'to' => explode(',', env('CONTACT_MF_TO')),
            'cc' => explode(',', env('CONTACT_MF_CC')),
        ],

        'Sonstiges' => [
            'to' => explode(',', env('CONTACT_MISC_TO')),
            'cc' => explode(',', env('CONTACT_MISC_CC')),
        ],
    ],

];
