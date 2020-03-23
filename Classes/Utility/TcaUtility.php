<?php

declare(strict_types = 1);

/**
 * TCA functions.
 */

namespace HDNET\Focuspoint\Utility;

/**
 * TCA functions.
 */
class TcaUtility
{
    /**
     * Get field configuration.
     */
    public static function getBaseConfiguration(): array
    {
        return [
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => -100,
                    'upper' => 100,
                ],
                'wizards' => [
                    '_DISTANCE' => '10',
                    'focuspoint' => [
                        'type' => 'script',
                        'icon' => 'EXT:focuspoint/ext_icon.svg',
                        'module' => [
                            'name' => 'focuspoint',
                        ],
                    ],
                    'slider' => [
                        'type' => 'slider',
                        'step' => '1',
                    ],
                ],
            ],
        ];
    }
}
