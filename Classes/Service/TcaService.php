<?php
/**
 * TcaService
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * TcaService
 *
 * @author Tim Lochmüller
 */
class TcaService extends AbstractService
{

    /**
     * Add the custom elements
     *
     * @param array $params
     * @param object $parent
     */
    public function addDatabaseItems(array &$params, $parent)
    {
        $customItems = $this->getCustomItems();
        if (empty($customItems)) {
            array_unshift($params['items'], [
                '',
                ''
            ]);
            return;
        }

        // Add element
        foreach ($customItems as $item) {
            array_unshift($params['items'], [
                $item['dimension'] . ' / ' . $item['title'],
                $item['identifier']
            ]);
        }
        array_unshift($params['items'], [
            'Custom',
            '--div--'
        ]);
        array_unshift($params['items'], [
            '',
            ''
        ]);
    }

    /**
     * Get custom elements
     *
     * @return array
     */
    protected function getCustomItems()
    {
        $databaseConnection = GlobalUtility::getDatabaseConnection();
        return (array)$databaseConnection->exec_SELECTgetRows(
            '*',
            'tx_focuspoint_domain_model_dimension',
            '1=1' . BackendUtility::deleteClause('tx_focuspoint_domain_model_dimension')
        );
    }
}
