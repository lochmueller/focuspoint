<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Utility;

class TcaUtility
{
    /**
     * Get field configuration.
     */
    public static function getBaseConfiguration(string $label): array
    {
        return [
            'exclude' => 1,
            'label' => $label,
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
                        'icon' => 'EXT:focuspoint/Resources/Public/Icons/Extension.svg',
                        'module' => [
                            'name' => 'focuspoint',
                        ],
                    ],
                ],
                'slider' => [
                    'step' => '1',
                ],
            ],
        ];
    }
}
