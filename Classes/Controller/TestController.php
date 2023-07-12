<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class TestController extends ActionController
{
    public function __construct(protected \TYPO3\CMS\Core\Resource\FileRepository $fileRepository)
    {
    }

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
