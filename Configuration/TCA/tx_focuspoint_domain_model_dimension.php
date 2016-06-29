<?php

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Domain\Model\Dimension;

$base = ModelUtility::getTcaInformation(Dimension::class);

$custom = [
    'ctrl' => [
        'rootLevel' => 1,
    ],
    'columns' => [
        'identifier' => [
            'config' => [
                'eval' => 'alphanum_x,lower,nospace,trim,required,unique',
            ],
        ],
    ],
];

return ArrayUtility::mergeRecursiveDistinct($base, $custom);
