<?php
/**
 * TCA functions.
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * TCA functions.
 */
class TcaUtility
{
    /**
     * Get field configuration.
     *
     * @return array
     */
    public static function getBaseConfiguration()
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
                        'icon' => ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
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
