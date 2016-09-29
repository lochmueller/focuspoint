<?php
/**
 * Utility functions for files
 *
 * @package Focuspoint\Utility
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Utility functions for files
 *
 * @author Tim Lochmüller
 */
class FileUtility
{

    /**
     * Get the file object of the given cell information
     *
     * @param int $uid
     *
     * @return \TYPO3\CMS\Core\Resource\File
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public static function getFileByMetaData($uid)
    {
        /** @var DatabaseConnection $database */
        $database = $GLOBALS['TYPO3_DB'];
        $row = $database->exec_SELECTgetSingleRow('file', 'sys_file_metadata', 'uid=' . $uid);

        if (!isset($row['file'])) {
            throw new \Exception('File not found in metadata', 1475144028);
        }

        return self::getFileByUid((int)$row['file']);
    }

    /**
     * Get the file object of the given cell information
     *
     * @param int $uid
     *
     * @return \TYPO3\CMS\Core\Resource\File
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public static function getFileByUid($uid)
    {
        return ResourceFactory::getInstance()
            ->getFileObject($uid);
    }
}
