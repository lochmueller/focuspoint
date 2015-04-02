<?php
/**
 * File metadata
 *
 * @package Focuspoint\Domain\Model
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * File metadata
 *
 * @author Tim Lochmüller
 * @db     sys_file_metadata
 */
class FileMetadata extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Focus point Y
	 *
	 * @var int
	 * @db
	 */
	protected $focusPointY;

	/**
	 * Focus point X
	 *
	 * @var int
	 * @db
	 */
	protected $focusPointX;

	/**
	 * Get Y
	 *
	 * @return int
	 */
	public function getFocusPointY() {
		return $this->focusPointY;
	}

	/**
	 * Set Y
	 *
	 * @param int $focusPointY
	 */
	public function setFocusPointY($focusPointY) {
		$this->focusPointY = $focusPointY;
	}

	/**
	 * Get X
	 *
	 * @return int
	 */
	public function getFocusPointX() {
		return $this->focusPointX;
	}

	/**
	 * Set X
	 *
	 * @param int $focusPointX
	 */
	public function setFocusPointX($focusPointX) {
		$this->focusPointX = $focusPointX;
	}
}
