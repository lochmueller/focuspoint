<?php

declare(strict_types=1);

$ll = 'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:tx_focuspoint_domain_model_dimension';

return [
    'ctrl' => [
        'title' => $ll,
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
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
        'title' => [
            'label' => $ll . '.title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
            ],
        ],
        'identifier' => [
            'label' => $ll . '.identifier',
            'config' => [
                'type' => 'input',
                'eval' => 'alphanum_x,lower,nospace,trim,required,unique',
            ],
        ],
        'dimension' => [
            'label' => $ll . '.dimension',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,nospace,required',
            ],
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'title,identifier,dimension',
        ],
    ],
];
