<?php
/**
 * Abstract raw repository
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract raw repository
 */
abstract class AbstractRawRepository
{

    /**
     * Get the tablename
     *
     * @return string
     */
    abstract protected function getTableName(): string;

    /**
     * Find by uid
     *
     * @param int $uid
     * @return array|null
     */
    public function findByUid(int $uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->getTableName());
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('uid', $uid)
            )
            ->execute()
            ->fetchAll();
        return isset($rows[0]) ? $rows[0] : null;
    }

    /**
     * Update by uid
     *
     * @param int $uid
     * @param array $values
     */
    public function updateByUid(int $uid, array $values)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->getTableName());
        $connection->update(
            $this->getTableName(),
            $values,
            ['uid' => (int) $uid]
        );
    }

}
