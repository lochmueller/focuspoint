<?php

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
     * @param int $uid
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
            ->fetchAll();

        return $rows[0] ?? null;
    }

    /**
     * Find all.
     *
     * @return array
     */
    public function findAll(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return (array) $queryBuilder->select('*')
            ->from($this->getTableName())
            ->execute()
            ->fetchAll();
    }

    /**
     * Update by uid.
     *
     * @param int   $uid
     * @param array $values
     */
    public function update(int $uid, array $values)
    {
        $this->getConnection()->update(
            $this->getTableName(),
            $values,
            ['uid' => (int) $uid]
        );
    }

    /**
     * Insert.
     *
     * @param array $values
     */
    public function insert(array $values)
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
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->getTableName());
    }

    /**
     * Get query builder.
     *
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->getTableName());
    }

    /**
     * Get the tablename.
     *
     * @return string
     */
    abstract protected function getTableName(): string;
}
