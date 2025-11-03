<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;

class WizardService extends AbstractService
{
    public function __construct(private readonly IconFactory $iconFactory) {}

    /**
     * Get the wizard button with the given URI.
     */
    public function getWizardButton(?string $uri = null, bool $addDataActionNavigation = false, bool $light = false): string
    {
        // When the icon is rendered inline, the currentColor is inherited (Light/Dark)
        $iconHtml = $this->iconFactory
            ->getIcon('focuspoint-filestandalone', IconSize::SMALL)
            ->render('inline');


        $label = $GLOBALS['LANG']->sL('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:focuspoint.wizard');
        if (null === $uri) {
            $label .= ' ' . $GLOBALS['LANG']->sL(
                'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:focuspoint.wizard.imagesonly'
            );

            return '<span class="btn btn-default disabled" title="' . $label . '">' . $iconHtml . '</span>';
        }

        $dataAction = $addDataActionNavigation ? ' data-action-navigate="' . $uri . '"' : '';

        return '<a href="' . $uri . '"' . $dataAction . ' class="btn btn-default" title="' . $label . '">' . $iconHtml . '</a>';
    }

}
