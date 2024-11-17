<?php

declare(strict_types=1);

use HDNET\Focuspoint\Utility\TcaUtility;

$ll = 'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:tx_focuspoint_domain_model_filestandalone';

return [
    'ctrl' => [
        'title' => $ll,
        'label' => 'relative_file_path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
        'hideTable' => true,
        'rootLevel' => 1,
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'columns' => [
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],
        'relative_file_path' => [
            'exclude' => false,
            'label' => $ll . '.relative_file_path',
            'config' => [
                'type' => 'input',
            ],
        ],
        'focus_point_y' => TcaUtility::getBaseConfiguration($ll . '.focus_point_y'),
        'focus_point_x' => TcaUtility::getBaseConfiguration($ll . '.focus_point_x'),
    ],
    'types' => [
        0 => [
            'showitem' => 'relative_file_path,focus_point_y,focus_point_x',
        ],
    ],
];
