<?php
/**
 * Test controller
 *
 * @package Focuspoint\Controller
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Test controller
 *
 * @author Tim Lochmüller
 */
class TestController extends ActionController
{

    /**
     * File repository
     *
     * @var \TYPO3\CMS\Core\Resource\FileRepository
     * @inject
     */
    protected $fileRepository;

    /**
     * Test action
     *
     * @plugin Test
     */
    public function testAction()
    {
        $contentElement = $this->configurationManager->getContentObject()->data;
        $fileReferences = $this->fileRepository->findByRelation('tt_content', 'image', $contentElement['uid']);

        $this->view->assignMultiple([
            'fileReferences' => $fileReferences,
            'customRatio' => $contentElement['image_ratio']
        ]);
    }
}
