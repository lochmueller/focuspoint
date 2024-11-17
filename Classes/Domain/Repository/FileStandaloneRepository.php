<?php

declare(strict_types=1);


namespace HDNET\Focuspoint\Domain\Repository;


class FileStandaloneRepository extends AbstractRawRepository
{

    public function findOneByRelativeFilePath(string $relativeFilePath): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('relative_file_path', $queryBuilder->createNamedParameter($relativeFilePath))
            )
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        return $rows[0] ?? null;
    }


    protected function getTableName(): string
    {
        return 'tx_focuspoint_domain_model_filestandalone';
    }
}
