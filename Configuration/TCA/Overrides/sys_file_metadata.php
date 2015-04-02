<?php

/**
 * Base TCA generation for the table sys_file_metadata
 */

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['sys_file_metadata'] = ModelUtility::getTcaOverrideInformation('focuspoint', 'sys_file_metadata');

$overridePointConfiguration = array(
	'config' => array(
		'size'    => 4,
		'eval'    => 'trim,int',
		'range'   => array(
			'lower' => -100,
			'upper' => 100
		),
		'wizards' => array(
			'_DISTANCE'  => '10',
			'focuspoint' => array(
				'type'   => 'script',
				'icon'   => ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
				'module' => array(
					'name' => 'focuspoint'
				),
			),
			'slider'     => array(
				'type' => 'slider',
				'step' => '1',
			),
		),
	),
);

$custom = array(
	'columns' => array(
		'focus_point_y' => $overridePointConfiguration,
		'focus_point_x' => $overridePointConfiguration,
	),
);

$GLOBALS['TCA']['sys_file_metadata'] = ArrayUtility::mergeRecursiveDistinct($GLOBALS['TCA']['sys_file_metadata'], $custom);

ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'focus_point_y,focus_point_x');