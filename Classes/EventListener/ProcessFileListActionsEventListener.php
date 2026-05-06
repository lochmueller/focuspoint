<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\EventListener;

use HDNET\Focuspoint\Service\WizardService;
use HDNET\Focuspoint\Utility\ImageUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ComponentFactory;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Filelist\Event\ProcessFileListActionsEvent;

class ProcessFileListActionsEventListener
{
    public function __construct(
        protected WizardService $wizardService,
        protected ComponentFactory $componentFactory,
    ) {}

    public function __invoke(ProcessFileListActionsEvent $event): void
    {
        $resource = $event->getResource();
        if (!$resource instanceof File || !ImageUtility::isValidFileExtension($resource->getExtension())) {
            return;
        }

        // configure item
        $wizardArguments = [
            'P' => [
                'metaUid' => $resource->getMetaData()->get()['uid'],
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
            ],
        ];
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $wizardUri = (string) $uriBuilder->buildUriFromRoute('focuspoint', $wizardArguments);

        // Add item
        $button = $this->componentFactory->createFullyRenderedButton()->setHtmlSource($this->wizardService->getWizardButton($wizardUri, false));
        $event->setAction($button, 'focuspoint');
    }
}
