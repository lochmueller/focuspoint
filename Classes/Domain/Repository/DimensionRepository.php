<?php

declare(strict_types=1);

/**
 * DimensionRepository.
 */

namespace HDNET\Focuspoint\Domain\Repository;

/**
 * DimensionRepository.
 */
class DimensionRepository extends AbstractRawRepository
{
    /**
     * Find one by identifier.
     */
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

    /**
     * Get the tablename.
     */
    protected function getTableName(): string
    {
        return 'tx_focuspoint_domain_model_dimension';
    }
}
