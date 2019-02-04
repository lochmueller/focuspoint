<?php
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
     *
     * @param string $identifier
     *
     * @return array|null
     */
    public function findOneByIdentifier(string $identifier)
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($identifier))
            )
            ->execute()
            ->fetchAll();

        return $rows[0] ?? null;
    }

    /**
     * Get the tablename.
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return 'tx_focuspoint_domain_model_dimension';
    }
}
