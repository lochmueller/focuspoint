<?php

declare(strict_types=1);

/**
 * Helper class for wizard handling.
 */

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * WizardService.
 */
class WizardService extends AbstractService
{
    /**
     * Get the wizard button with the given URI.
     */
    public function getWizardButton(?string $uri = null, bool $addDataActionNavigation = false, bool $light = false): string
    {
        $spriteIcon = $this->getWizardIcon($light);
        $label = LocalizationUtility::translate('focuspoint.wizard', 'focuspoint');
        if (null === $uri) {
            $label .= ' '.LocalizationUtility::translate('focuspoint.wizard.imagesonly', 'focuspoint');

            return '<span class="btn btn-default disabled" title="'.$label.'">'.$spriteIcon.'</span>';
        }

        $dataAction = $addDataActionNavigation ? ' data-action-navigate="'.$uri.'"' : '';

        return '<a href="'.$uri.'"'.$dataAction.' class="btn btn-default" title="'.$label.'">'.$spriteIcon.'</a>';
    }

    /**
     * Get the wizard icon.
     *
     * @param mixed $light
     */
    protected function getWizardIcon($light = false): string
    {
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $icon = $iconFactory->getIcon(
            'tcarecords-tx_focuspoint_domain_model_filestandalone-default'.($light ? '-light' : ''),
            Icon::SIZE_SMALL,
            null
        );

        return $icon->render();
    }
}
