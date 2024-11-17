<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Domain\Repository;

class SysFileMetadataRepository extends AbstractRawRepository
{
    public function findOneByFileUid(int $fileUid): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('file', $fileUid)
            )
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        return $rows[0] ?? null;
    }

    protected function getTableName(): string
    {
        return 'sys_file_metadata';
    }
}
