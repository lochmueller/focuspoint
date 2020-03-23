<?php

declare(strict_types = 1);

/**
 * SysFileMetadata.
 */

namespace HDNET\Focuspoint\Domain\Repository;

/**
 *  SysFileMetadata.
 */
class SysFileMetadataRepository extends AbstractRawRepository
{
    /**
     * Find one by file.
     */
    public function findOneByFileUid(int $fileUid): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('file', $fileUid)
            )
            ->execute()
            ->fetchAll()
        ;

        return $rows[0] ?? null;
    }

    /**
     * Get the tablename.
     */
    protected function getTableName(): string
    {
        return 'sys_file_metadata';
    }
}
