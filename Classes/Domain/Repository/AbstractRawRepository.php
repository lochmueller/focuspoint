<?php

declare(strict_types = 1);

/**
 * Abstract raw repository.
 */

namespace HDNET\Focuspoint\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract raw repository.
 */
abstract class AbstractRawRepository
{
    /**
     * Find by uid.
     *
     * @return array|null
     */
    public function findByUid(int $uid)
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('uid', $uid)
            )
            ->execute()
            ->fetchAll()
        ;

        return $rows[0] ?? null;
    }

    /**
     * Find all.
     */
    public function findAll(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return (array)$queryBuilder->select('*')
            ->from($this->getTableName())
            ->execute()
            ->fetchAll()
        ;
    }

    /**
     * Update by uid.
     */
    public function update(int $uid, array $values): void
    {
        $this->getConnection()->update(
            $this->getTableName(),
            $values,
            ['uid' => (int)$uid]
        );
    }

    /**
     * Insert.
     */
    public function insert(array $values): void
    {
        $this->getConnection()->insert(
            $this->getTableName(),
            $values
        );
    }

    /**
     * Get connection.
     *
     * @return \TYPO3\CMS\Core\Database\Connection
     */
    protected function getConnection()
    {
        return $this->getConnectionPool()->getConnectionForTable($this->getTableName());
    }

    /**
     * Get query builder.
     *
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($this->getTableName());
    }

    /**
     * Get the tablename.
     */
    abstract protected function getTableName(): string;

    /**
     * Get connection pool.
     *
     * @return ConnectionPool
     */
    private function getConnectionPool()
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
