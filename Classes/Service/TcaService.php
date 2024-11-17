<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Domain\Repository\DimensionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaService extends AbstractService
{
    /**
     * Add the custom elements.
     *
     * @param object $parent
     */
    public function addDatabaseItems(array &$params, $parent): void
    {
        $customItems = $this->getCustomItems();
        if (empty($customItems)) {
            array_unshift($params['items'], [
                '',
                '',
            ]);

            return;
        }

        // Add element
        foreach ($customItems as $item) {
            array_unshift($params['items'], [
                $item['dimension'].' / '.$item['title'],
                $item['identifier'],
            ]);
        }
        array_unshift($params['items'], [
            'Custom',
            '--div--',
        ]);
        array_unshift($params['items'], [
            '',
            '',
        ]);
    }

    /**
     * Get custom elements.
     */
    protected function getCustomItems(): array
    {
        return GeneralUtility::makeInstance(DimensionRepository::class)->findAll();
    }
}
