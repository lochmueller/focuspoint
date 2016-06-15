<?php

/**
 * Base TCA generation for the table tt_content
 */

$GLOBALS['TCA']['tt_content'] = \HDNET\Autoloader\Utility\ModelUtility::getTcaOverrideInformation(
    'focuspoint',
    'tt_content'
);

$custom = [
    'columns' => [
        'image_ratio' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        ''
                    ],
                    [
                        '1:1 / Square',
                        '1:1'
                    ],
                    [
                        '2:1 / Landscape block',
                        '2:1'
                    ],
                    [
                        '1:2 / Portrait block',
                        '1:2'
                    ],
                    [
                        'Images (landscape)',
                        '--div--'
                    ],
                    [
                        '10×7',
                        '10:7'
                    ],
                    [
                        '13×9',
                        '13:9'
                    ],
                    [
                        'Postcard',
                        '14.8:10.5'
                    ],
                    [
                        '13×10',
                        '13:10'
                    ],
                    [
                        '15×10',
                        '15:10'
                    ],
                    [
                        '18×13',
                        '18:13'
                    ],
                    [
                        '19×13',
                        '19:13'
                    ],
                    [
                        '24×18',
                        '24:18'
                    ],
                    [
                        'Images (Portrait)',
                        '--div--'
                    ],
                    [
                        '7×10',
                        '7:10'
                    ],
                    [
                        '9×13',
                        '9:13'
                    ],
                    [
                        'Postcard',
                        '10.5:14.8'
                    ],
                    [
                        '10×13',
                        '10:13'
                    ],
                    [
                        '10×15',
                        '10:15'
                    ],
                    [
                        '13×18',
                        '13:18'
                    ],
                    [
                        '13×19',
                        '13:19'
                    ],
                    [
                        '18×24',
                        '18:24'
                    ],
                    [
                        'Movie',
                        '--div--'
                    ],
                    [
                        '4:3 / original 35 mm silent movie',
                        '4:3'
                    ],
                    [
                        '15:10 / 35 mm movie',
                        '15:10'
                    ],
                    [
                        '16:9 / Widescreen Default (HD)',
                        '16:9'
                    ],
                    [
                        '16:10 / Widescreen',
                        '16:10'
                    ],
                    [
                        '11:5, 22:10 / 70 mm standard',
                        '11:5'
                    ],
                    [
                        '64:27 / 4^3:3^3 television',
                        '64:27'
                    ],
                    [
                        '8:3, 24:9 / Full frame Super 16 mm',
                        '8:3'
                    ],
                ],
            ],
        ],
    ],
    'palettes' => [
        'image_settings' => [
            'showitem' => str_replace(
                'imageborder;',
                'image_ratio,imageborder;',
                $GLOBALS['TCA']['tt_content']['palettes']['image_settings']['showitem']
            )
        ],
    ],
];

$GLOBALS['TCA']['tt_content'] = \HDNET\Autoloader\Utility\ArrayUtility::mergeRecursiveDistinct(
    $GLOBALS['TCA']['tt_content'],
    $custom
);
