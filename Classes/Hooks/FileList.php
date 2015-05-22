<?php
/**
 * Extends the file list
 *
 * @package Focuspoint\Hooks
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Focuspoint\Utility\FileUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Filelist\FileListEditIconHookInterface;

/**
 * Extends the file list
 *
 * @author Tim Lochmüller
 * @hook   TYPO3_CONF_VARS|SC_OPTIONS|fileList|editIconsHook
 */
class FileList implements FileListEditIconHookInterface {

	/**
	 * Modifies edit icon array
	 *
	 * @param array                        $cells        Array of edit icons
	 * @param \TYPO3\CMS\Filelist\FileList $parentObject Parent object
	 *
	 * @return void
	 */
	public function manipulateEditIcons(&$cells, &$parentObject) {
		try {
			$metaUid = $this->getFileMetaUidByCells($cells);
			$file = FileUtility::getFileByMetaData($metaUid);
		} catch (\Exception $ex) {
			$cells['focuspoint'] = $this->getEmptyIcon();
			return;
		}

		// no Image?
		if ($file->getType() !== AbstractFile::FILETYPE_IMAGE) {
			$cells['focuspoint'] = $this->getEmptyIcon();
			return;
		}

		$wizardArguments = array(
			'P' => array(
				'uid'       => $metaUid,
				'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
			),
		);
		$wizardUri = BackendUtility::getModuleUrl('focuspoint', $wizardArguments);
		$cells['focuspoint'] = '<a href="' . $wizardUri . '" class="btn btn-default">' . IconUtility::getSpriteIcon('extensions-focuspoint-focuspoint') . '</a>';
	}

	/**
	 * Get the empty icon
	 *
	 * @return string
	 */
	protected function getEmptyIcon() {
		return '<span class="btn btn-default disabled">' . IconUtility::getSpriteIcon('empty-empty') . '</span>';
	}

	/**
	 * Get the file object of the given cell information
	 *
	 * @param array $cells
	 *
	 * @return int
	 * @throws \Exception
	 */
	protected function getFileMetaUidByCells($cells) {
		if (GeneralUtility::compat_version('7.2.0')) {
			$pattern = "/'([0-9]:.*?)'/";
			if (!preg_match($pattern, $cells['info'], $matches)) {
				throw new \Exception('No valid metadata information found', 24674575467452);
			}
			$resourceFactory = ResourceFactory::getInstance();
			$fileObject = $resourceFactory->getFileObjectFromCombinedIdentifier(str_replace('\\', '', $matches[1]));
			return (int)$fileObject->getUid();
		} else {
			$pattern = '/sys_file_metadata\]\[([0-9]*)\]/';
			if (!preg_match($pattern, $cells['editmetadata'], $matches)) {
				throw new \Exception('No valid metadata information found', 127846873264328);
			}
			return (int)$matches[1];
		}
	}
}
