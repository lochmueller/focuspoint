<?php

declare(strict_types=1);


// @todo add missing stuuff

return [
    'ctrl' => [
        'rootLevel' => 1,
    ],
    'columns' => [
        'title' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
            ],
        ],
        'identifier' => [
            'config' => [
                'type' => 'input',
                'eval' => 'alphanum_x,lower,nospace,trim,required,unique',
            ],
        ],
        'dimension' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,nospace,required',
            ],
        ],
    ],
];
