<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class TestController extends ActionController
{
    public function __construct(protected FileRepository $fileRepository) {}

    public function testAction(): ResponseInterface
    {
        $contentElement = $this->configurationManager->getContentObject()->data;

        $this->view->assignMultiple([
            'fileReferences' => $this->fileRepository->findByRelation('tt_content', 'image', $contentElement['uid']),
            'customRatio' => $contentElement['image_ratio'],
        ]);

        return $this->htmlResponse();
    }
}
