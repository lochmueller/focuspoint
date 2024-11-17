<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractRawRepository
{
    public function findByUid(int $uid): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('uid', $uid)
            )
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        return $rows[0] ?? null;
    }

    public function findAll(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        return (array) $queryBuilder->select('*')
            ->from($this->getTableName())
            ->executeQuery()
            ->fetchAllAssociative()
        ;
    }

    public function update(int $uid, array $values): void
    {
        $this->getConnection()->update(
            $this->getTableName(),
            $values,
            ['uid' => $uid]
        );
    }

    public function insert(array $values): void
    {
        $this->getConnection()->insert(
            $this->getTableName(),
            $values
        );
    }

    protected function getConnection(): Connection
    {
        return $this->getConnectionPool()->getConnectionForTable($this->getTableName());
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($this->getTableName());
    }

    abstract protected function getTableName(): string;

    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
