<?php

declare(strict_types=1);

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Domain\Model\Dimension;

$base = ModelUtility::getTcaInformation(Dimension::class);

$custom = [
    'ctrl' => [
        'rootLevel' => 1,
    ],
    'columns' => [
        'title' => [
            'config' => [
                'eval' => 'trim,required',
            ],
        ],
        'identifier' => [
            'config' => [
                'eval' => 'alphanum_x,lower,nospace,trim,required,unique',
            ],
        ],
        'dimension' => [
            'config' => [
                'eval' => 'trim,nospace,required',
            ],
        ],
    ],
];

return ArrayUtility::mergeRecursiveDistinct($base, $custom);
