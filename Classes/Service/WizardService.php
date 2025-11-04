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
    public function getWizardButton(?string $uri = null, bool $fileReferenceControls = true): string
    {
        // When the icon is rendered inline, the currentColor is inherited (Light/Dark)
        $iconHtml = $this->iconFactory
            ->getIcon('focuspoint-filestandalone', IconSize::SMALL)
            ->render('inline');


        $label = $GLOBALS['LANG']->sL('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:focuspoint');
        if (null === $uri) {
            $label .= ' ' . $GLOBALS['LANG']->sL(
                'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:focuspoint.wizard.imagesonly'
            );

            if ($fileReferenceControls) {
                return '<span class="btn btn-default" title="' . $label . '">' . $iconHtml . '</span>';
            } else {
                return '<span role="button" class="dropdown-item dropdown-item-spaced" title="' . $label . '">' . $iconHtml . '</a>';
            }
        }

        if ($fileReferenceControls) {
            return '<a href="' . $uri . '"' . ' data-action-navigate="' . $uri . '"' . ' class="btn btn-default" title="' . $label . '">' . $iconHtml . '</a>';
        } else {
            return '<a role="button" href="' . $uri . '"' . ' class="dropdown-item dropdown-item-spaced" title="' . $label . '">' . $iconHtml . '</a>';
        }

    }

}
