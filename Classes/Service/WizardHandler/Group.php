<?php

namespace HDNET\Focuspoint\Service\WizardHandler;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Group.
 */
class Group extends AbstractWizardHandler
{
    /**
     * The table name.
     */
    const TABLE = 'tx_focuspoint_domain_model_filestandalone';

    /**
     * Check if the handler can handle the current request.
     *
     * @return bool
     */
    public function canHandle()
    {
        return null !== $this->getRelativeFilePath();
    }

    /**
     * Return the current point.
     *
     * @return int[]
     */
    public function getCurrentPoint()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $rows = (array) $queryBuilder->select('uid', 'focus_point_x', 'focus_point_y')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('relative_file_path', $queryBuilder->createNamedParameter($this->getRelativeFilePath()))
            )
            ->execute()
            ->fetchAll();

        if (empty($rows)) {
            return [0, 0];
        }

        return $this->cleanupPosition([
            $rows[0]['focus_point_x'],
            $rows[0]['focus_point_y'],
        ]);
    }

    /**
     * Set the point (between -100 and 100).
     *
     * @param int $x
     * @param int $y
     */
    public function setCurrentPoint($x, $y)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
        $rows = (array) $queryBuilder->select('uid')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('relative_file_path', $queryBuilder->createNamedParameter($this->getRelativeFilePath()))
            )
            ->execute()
            ->fetchAll();

        $values = [
            'focus_point_x' => $x,
            'focus_point_y' => $y,
            'relative_file_path' => $this->getRelativeFilePath(),
        ];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE);
        if (!empty($rows)) {
            $connection->update(
                self::TABLE,
                $values,
                ['uid' => (int) $rows[0]['uid']]
            );
        } else {
            $connection->insert(
                self::TABLE,
                $values
            );
        }
    }

    /**
     * Get the public URL for the current handler.
     *
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->displayableImageUrl($this->getRelativeFilePath());
    }

    /**
     * get the arguments for same request call.
     *
     * @return array
     */
    public function getArguments()
    {
        $parameter = GeneralUtility::_GET();
        $p = $parameter['P'];

        return [
            'P' => [
                'table' => $p['table'],
                'field' => $p['field'],
                'file' => $p['file'],
            ],
        ];
    }

    /**
     * get the file name.
     *
     * @return string|null
     */
    protected function getRelativeFilePath()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return;
        }
        $p = $parameter['P'];
        if (!isset($p['table']) || !isset($p['field']) || !isset($p['file'])) {
            return;
        }
        if (!isset($GLOBALS['TCA'][$p['table']])) {
            return;
        }
        $tableTca = $GLOBALS['TCA'][$p['table']];
        if (!isset($tableTca['columns'][$p['field']])) {
            return;
        }
        $fieldTca = $tableTca['columns'][$p['field']];

        $uploadFolder = $fieldTca['config']['uploadfolder'] ?? '';
        $baseFolder = '';
        if ('' !== \trim($uploadFolder, '/')) {
            $baseFolder = \rtrim($uploadFolder, '/') . '/';
        }

        $filePath = $baseFolder . $p['file'];
        if (!\is_file(GeneralUtility::getFileAbsFileName($filePath))) {
            return;
        }

        return $filePath;
    }
}
