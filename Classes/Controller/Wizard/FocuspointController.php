<?php

declare(strict_types = 1);

/**
 * Wizard controller.
 */

namespace HDNET\Focuspoint\Controller\Wizard;

use HDNET\Focuspoint\Service\WizardHandler\AbstractWizardHandler;
use HDNET\Focuspoint\Service\WizardHandler\File;
use HDNET\Focuspoint\Service\WizardHandler\FileReference;
use HDNET\Focuspoint\Service\WizardHandler\Group;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Wizard controller.
 */
class FocuspointController
{
    /**
     * Main action.
     *
     * @throws \Exception
     *
     * @return string
     */
    public function main()
    {
        $handler = $this->getCurrentHandler();
        $parameter = GeneralUtility::_GET();
        if (isset($parameter['save'])) {
            if (\is_object($handler)) {
                $handler->setCurrentPoint($parameter['xValue'] * 100, $parameter['yValue'] * 100);
            }
            HttpUtility::redirect($parameter['P']['returnUrl']);
        }
        $saveArguments = [
            'save' => 1,
            'P' => [
                'returnUrl' => $parameter['P']['returnUrl'],
            ],
        ];

        /** @var StandaloneView $template */
        $template = GeneralUtility::makeInstance(StandaloneView::class);
        $template->setTemplatePathAndFilename(ExtensionManagementUtility::extPath(
            'focuspoint',
            'Resources/Private/Templates/Wizard/Focuspoint.html'
        ));

        if (\is_object($handler)) {
            ArrayUtility::mergeRecursiveWithOverrule($saveArguments, $handler->getArguments());
            list($x, $y) = $handler->getCurrentPoint();
            $template->assign('filePath', $handler->getPublicUrl());
            $template->assign('currentLeft', (($x + 100) / 2) . '%');
            $template->assign('currentTop', (($y - 100) / -2) . '%');
        }

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $template->assign('saveUri', $uriBuilder->buildUriFromRoute('focuspoint', $saveArguments));

        return $template->render();
    }

    /**
     * Returns the Module menu for the AJAX request.
     *
     * @param ResponseInterface $response
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response = null): ResponseInterface
    {
        if (null === $response) {
            $response = new HtmlResponse('');
        }
        $content = $this->main();
        $response->getBody()->write($content);

        return $response;
    }

    /**
     * Get the current handler.
     *
     * @return AbstractWizardHandler|null
     */
    protected function getCurrentHandler()
    {
        foreach ($this->getWizardHandler() as $handler) {
            /** @var AbstractWizardHandler $handler */
            if ($handler->canHandle()) {
                return $handler;
            }
        }
    }

    /**
     * Get the wizard handler.
     */
    protected function getWizardHandler(): array
    {
        return [
            GeneralUtility::makeInstance(File::class),
            GeneralUtility::makeInstance(FileReference::class),
            GeneralUtility::makeInstance(Group::class),
        ];
    }
}
