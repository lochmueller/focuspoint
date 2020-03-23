<?php

declare(strict_types = 1);

/**
 * Test controller.
 */

namespace HDNET\Focuspoint\Controller;

use HDNET\Autoloader\Annotation\Plugin;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Test controller.
 */
class TestController extends ActionController
{
    /**
     * File repository.
     *
     * @var \TYPO3\CMS\Core\Resource\FileRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $fileRepository;

    /**
     * Test action.
     *
     * @Plugin("Test")
     */
    public function testAction(): void
    {
        $contentElement = $this->configurationManager->getContentObject()->data;
        $fileReferences = $this->fileRepository->findByRelation('tt_content', 'image', $contentElement['uid']);

        $this->view->assignMultiple([
            'fileReferences' => $fileReferences,
            'customRatio' => $contentElement['image_ratio'],
        ]);
    }
}
