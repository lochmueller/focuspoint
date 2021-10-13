<?php

declare(strict_types=1);

/**
 * Utility functions for files.
 */

namespace HDNET\Focuspoint\Utility;

use HDNET\Focuspoint\Domain\Repository\SysFileMetadataRepository;
use TYPO3\CMS\Core\Resource\File;
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
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public static function getFileByMetaData(int $uid): File
    {
        $row = GeneralUtility::makeInstance(SysFileMetadataRepository::class)->findByUid($uid);
        if (!isset($row['file'])) {
            throw new \Exception('File not found in metadata', 1475144028);
        }

        return self::getFileByUid((int) $row['file']);
    }

    /**
     * Get the file object of the given cell information.
     *
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public static function getFileByUid(int $uid): File
    {
        return GeneralUtility::makeInstance(ResourceFactory::class)
            ->getFileObject($uid)
        ;
    }
}
