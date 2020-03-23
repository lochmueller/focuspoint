<?php

declare(strict_types = 1);

/**
 * Utility functions for files.
 */

namespace HDNET\Focuspoint\Utility;

use HDNET\Focuspoint\Domain\Repository\SysFileMetadataRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility functions for files.
 */
class FileUtility
{
    /**
     * Get the file object of the given cell information.
     *
     * @param int $uid
     *
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     *
     * @return \TYPO3\CMS\Core\Resource\File
     */
    public static function getFileByMetaData($uid)
    {
        $row = GeneralUtility::makeInstance(SysFileMetadataRepository::class)->findByUid((int)$uid);
        if (!isset($row['file'])) {
            throw new \Exception('File not found in metadata', 1475144028);
        }

        return self::getFileByUid((int)$row['file']);
    }

    /**
     * Get the file object of the given cell information.
     *
     * @param int $uid
     *
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     *
     * @return \TYPO3\CMS\Core\Resource\File
     */
    public static function getFileByUid($uid)
    {
        return ResourceFactory::getInstance()
            ->getFileObject($uid)
        ;
    }
}
