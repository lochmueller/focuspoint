<?php

/**
 * Base TCA generation for the table tt_content
 */

$GLOBALS['TCA']['tt_content'] = \HDNET\Autoloader\Utility\ModelUtility::getTcaOverrideInformation('focuspoint', 'tt_content');

$custom = array(
	'columns'  => array(
		'image_ratio' => array(
			'config' => array(
				'type'  => 'select',
				'items' => array(
					array(
						'',
						''
					),
					array(
						'1:1 / Square',
						'1:1'
					),
					array(
						'Images (landscape)',
						'--div--'
					),
					array(
						'10×7',
						'10:7'
					),
					array(
						'13×9',
						'13:9'
					),
					array(
						'Postcard',
						'14.8:10.5'
					),
					array(
						'13×10',
						'13:10'
					),
					array(
						'15×10',
						'15:10'
					),
					array(
						'18×13',
						'18:13'
					),
					array(
						'19×13',
						'19:13'
					),
					array(
						'24×18',
						'24:18'
					),
					array(
						'Images (Portrait)',
						'--div--'
					),
					array(
						'7×10',
						'7:10'
					),
					array(
						'9×13',
						'9:13'
					),
					array(
						'Postcard',
						'10.5:14.8'
					),
					array(
						'10×13',
						'10:13'
					),
					array(
						'10×15',
						'10:15'
					),
					array(
						'13×18',
						'13:18'
					),
					array(
						'13×19',
						'13:19'
					),
					array(
						'18×24',
						'18:24'
					),
					array(
						'Movie',
						'--div--'
					),
					array(
						'4:3 / original 35 mm silent movie',
						'4:3'
					),
					array(
						'15:10 / 35 mm movie',
						'15:10'
					),
					array(
						'16:9 / Widescreen Default (HD)',
						'16:9'
					),
					array(
						'16:10 / Widescreen',
						'16:10'
					),
					array(
						'11:5, 22:10 / 70 mm standard',
						'11:5'
					),
					array(
						'64:27 / 4^3:3^3 television',
						'64:27'
					),
					array(
						'8:3, 24:9 / Full frame Super 16 mm',
						'8:3'
					),
				),
			),
		),
	),
	'palettes' => array(
		'image_settings' => array(
			'showitem' => str_replace('imageborder;', 'image_ratio,imageborder;', $GLOBALS['TCA']['tt_content']['palettes']['image_settings']['showitem'])
		),
	),
);

$GLOBALS['TCA']['tt_content'] = \HDNET\Autoloader\Utility\ArrayUtility::mergeRecursiveDistinct($GLOBALS['TCA']['tt_content'], $custom);