<?php

declare(strict_types=1);

/**
 * Test controller.
 */

namespace HDNET\Focuspoint\Controller;

use Psr\Http\Message\ResponseInterface;
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
     *
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $fileRepository;

    /**
     * Test action.
     */
    public function testAction(): ResponseInterface
    {
        $contentElement = $this->configurationManager->getContentObject()->data;
        $fileReferences = $this->fileRepository->findByRelation('tt_content', 'image', $contentElement['uid']);

        $this->view->assignMultiple([
            'fileReferences' => $fileReferences,
            'customRatio' => $contentElement['image_ratio'],
        ]);

        return $this->htmlResponse($this->view->render());
    }
}
