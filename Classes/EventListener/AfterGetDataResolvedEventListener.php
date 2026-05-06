<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\EventListener;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\Event\AfterGetDataResolvedEvent;

class AfterGetDataResolvedEventListener
{
    public function __invoke(AfterGetDataResolvedEvent $event): void
    {
        $parts = explode(':', $event->getParameterString());
        if (!isset($parts[0], $parts[1]) || $parts[0] !== 'fp') {
            return;
        }

        $fileObject = $event->getContentObjectRenderer()->getCurrentFile();
        if (!$fileObject instanceof FileReference) {
            return;
        }

        $metaData = $fileObject->getOriginalFile()->getMetaData()->get();
        $axis = mb_substr($parts[1], 0, 1);

        switch ($parts[1]) {
            case 'x':
            case 'y':
                $event->setResult($metaData['focus_point_' . $parts[1]] / 100);
                return;

            case 'xp':
            case 'yp':
                $event->setResult((float) $metaData['focus_point_' . $axis]);
                return;

            case 'xp_positive':
                $event->setResult((int) (abs($metaData['focus_point_' . $axis] + 100) / 2));
                return;

            case 'yp_positive':
                $event->setResult((int) (abs($metaData['focus_point_' . $axis] - 100) / 2));
                return;

            case 'w':
            case 'h':
                $fileName = GeneralUtility::getFileAbsFileName($fileObject->getPublicUrl());
                if (file_exists($fileName)) {
                    $sizes = getimagesize($fileName);
                    $event->setResult($sizes[$parts[1] === 'w' ? 0 : 1]);
                }
                return;
        }
    }
}