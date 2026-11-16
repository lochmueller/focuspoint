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

    /**
     * Get a wizard button for records that are not saved yet.
     * Uses the TYPO3 core modal markup trigger, so no inline JavaScript is needed (CSP compliant).
     */
    public function getSaveFirstButton(): string
    {
        $iconHtml = $this->iconFactory
            ->getIcon('focuspoint-filestandalone', IconSize::SMALL)
            ->render('inline');

        $languagePrefix = 'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:';
        $label = $GLOBALS['LANG']->sL($languagePrefix . 'focuspoint');
        $title = $GLOBALS['LANG']->sL($languagePrefix . 'focuspoint.wizard.saveFirst.title');
        $message = $GLOBALS['LANG']->sL($languagePrefix . 'focuspoint.wizard.saveFirst.message');

        return '<button type="button" class="btn btn-default t3js-modal-trigger"'
            . ' title="' . htmlspecialchars($label) . '"'
            . ' data-severity="warning"'
            . ' data-title="' . htmlspecialchars($title) . '"'
            . ' data-content="' . htmlspecialchars($message) . '"'
            . '>' . $iconHtml . '</button>';
    }
}
