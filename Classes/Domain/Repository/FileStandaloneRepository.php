<?php
/**
 * FileStandalone.
 */

namespace HDNET\Focuspoint\Domain\Repository;

/**
 * FileStandalone.
 */
class FileStandaloneRepository extends AbstractRawRepository
{
    /**
     * Find one by relative file path.
     *
     * @param string $relativeFilePath
     *
     * @return array|null
     */
    public function findOneByRelativeFilePath(string $relativeFilePath)
    {
        $queryBuilder = $this->getQueryBuilder();
        $rows = $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where(
                $queryBuilder->expr()->eq('relative_file_path', $queryBuilder->createNamedParameter($relativeFilePath))
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
        return 'tx_focuspoint_domain_model_filestandalone';
    }
}
