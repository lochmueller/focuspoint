<?php

/**
 * SysFileMetadata
 *
 */

namespace HDNET\Focuspoint\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *  SysFileMetadata
 */
class SysFileMetadataRepository extends AbstractRawRepository
{

    /**
     * Get the tablename
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return 'sys_file_metadata';
    }

    /**
     * Find by file
     *
     * @param int $fileUid
     * @return array|null
     */
    public function findByFileUid(int $fileUid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->getTableName());
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('file', $fileUid)
            )
            ->execute()
            ->fetchAll();

        return isset($rows[0]) ? $rows[0] : null;
    }
}
