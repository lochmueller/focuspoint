<?php

declare(strict_types=1);


namespace HDNET\Focuspoint\Domain\Repository;

class DimensionRepository extends AbstractRawRepository
{
    public function findOneByIdentifier(string $identifier): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($identifier))
            )
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        return $rows[0] ?? null;
    }

    protected function getTableName(): string
    {
        return 'tx_focuspoint_domain_model_dimension';
    }
}
