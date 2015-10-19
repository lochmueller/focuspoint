<?php
/**
 * TCA functions
 *
 * @package Focuspoint\Utility
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * TCA functions
 *
 * @author Tim Lochmüller
 */
class TcaUtility
{

    /**
     * Get field configuration
     *
     * @return array
     */
    public static function getBaseConfiguration()
    {
        return array(
            'config' => array(
                'type' => 'text',
                'size' => 4,
                'eval' => 'trim,int',
                'range' => array(
                    'lower' => -100,
                    'upper' => 100
                ),
                'wizards' => array(
                    '_DISTANCE' => '10',
                    'focuspoint' => array(
                        'type' => 'script',
                        'icon' => ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
                        'module' => array(
                            'name' => 'focuspoint'
                        ),
                    ),
                    'slider' => array(
                        'type' => 'slider',
                        'step' => '1',
                    ),
                ),
            ),
        );
    }
}
