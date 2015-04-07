<?php
/**
 * Wizard controller
 *
 * @package Focuspoint\Controller\Wizard
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Controller\Wizard;

use HDNET\Focuspoint\Utility\FileUtility;
use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Wizard controller
 *
 * @author Tim Lochmüller
 */
class FocuspointController {

	/**
	 * Main action
	 *
	 * @throws \Exception
	 */
	public function main() {
		$parameter = GeneralUtility::_GET();
		$fileObject = FileUtility::getFileByMetaData((int)$parameter['P']['uid']);

		if (isset($parameter['save']) && $fileObject) {
			$values = array(
				'focus_point_y' => $parameter['yValue'] * 100,
				'focus_point_x' => $parameter['xValue'] * 100,
			);
			$uid = (int)$parameter['P']['uid'];
			GlobalUtility::getDatabaseConnection()
				->exec_UPDATEquery('sys_file_metadata', 'uid=' . $uid, $values);
			HttpUtility::redirect($parameter['P']['returnUrl']);
		}

		$saveArguments = array(
			'save' => 1,
			'P'    => array(
				'uid'       => $parameter['P']['uid'],
				'returnUrl' => $parameter['P']['returnUrl'],
			)
		);
		$saveUri = BackendUtility::getModuleUrl('focuspoint', $saveArguments);

		// current point
		$information = $this->getCurrentFocusPoint($parameter['P']['uid']);

		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $template */
		$template = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$template->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('focuspoint', 'Resources/Private/Templates/Wizard/Focuspoint.html'));
		$template->assign('filePath', $fileObject->getPublicUrl(TRUE));
		$template->assign('saveUri', $saveUri);
		$template->assign('currentLeft', (($information['focus_point_x'] + 100) / 2) . '%');
		$template->assign('currentTop', (($information['focus_point_y'] - 100) / -2) . '%');

		echo $template->render();
	}

	/**
	 * Get focus point information
	 *
	 * @param $uid
	 *
	 * @return array|FALSE|NULL
	 */
	protected function getCurrentFocusPoint($uid) {
		$row = GlobalUtility::getDatabaseConnection()
			->exec_SELECTgetSingleRow('focus_point_x, focus_point_y', 'sys_file_metadata', 'uid=' . $uid);
		$row['focus_point_x'] = MathUtility::forceIntegerInRange((int)$row['focus_point_x'], -100, 100, 0);
		$row['focus_point_y'] = MathUtility::forceIntegerInRange((int)$row['focus_point_y'], -100, 100, 0);
		return $row;
	}
}
