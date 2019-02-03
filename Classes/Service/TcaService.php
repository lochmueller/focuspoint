<?php

/**
 * TcaService.
 */

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TcaService.
 */
class TcaService extends AbstractService
{
    /**
     * Add the custom elements.
     *
     * @param array  $params
     * @param object $parent
     */
    public function addDatabaseItems(array &$params, $parent)
    {
        $customItems = $this->getCustomItems();
        if (empty($customItems)) {
            \array_unshift($params['items'], [
                '',
                '',
            ]);

            return;
        }

        // Add element
        foreach ($customItems as $item) {
            \array_unshift($params['items'], [
                $item['dimension'] . ' / ' . $item['title'],
                $item['identifier'],
            ]);
        }
        \array_unshift($params['items'], [
            'Custom',
            '--div--',
        ]);
        \array_unshift($params['items'], [
            '',
            '',
        ]);
    }

    /**
     * Get custom elements.
     *
     * @return array
     */
    protected function getCustomItems(): array
    {
        $table = 'tx_focuspoint_domain_model_dimension';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

        return (array) $queryBuilder->select('*')
            ->from($table)
            ->execute()
            ->fetchAll();
    }
}
