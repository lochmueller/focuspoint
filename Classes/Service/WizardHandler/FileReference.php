<?php

namespace HDNET\Focuspoint\Service\WizardHandler;

use HDNET\Focuspoint\Domain\Repository\SysFileMetadataRepository;
use HDNET\Focuspoint\Domain\Repository\SysFileReferenceRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * FileReference.
 */
class FileReference extends AbstractWizardHandler
{
    /**
     * Check if the handler can handle the current request.
     *
     * @return bool
     */
    public function canHandle()
    {
        return null !== $this->getReferenceUid();
    }

    /**
     * get the arguments for same request call.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            'P' => [
                'referenceUid' => $this->getReferenceUid(),
            ],
        ];
    }

    /**
     * Return the current point.
     *
     * @return int[]
     */
    public function getCurrentPoint()
    {
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        $properties = $reference->getProperties();

        return $this->cleanupPosition([
            $properties['focus_point_x'],
            $properties['focus_point_y'],
        ]);
    }

    /**
     * Get the public URL for the current handler.
     *
     * @return string
     */
    public function getPublicUrl()
    {
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());

        return $this->displayableImageUrl($reference->getPublicUrl());
    }

    /**
     * Set the point (between -100 and 100).
     *
     * @param int $x
     * @param int $y
     */
    public function setCurrentPoint($x, $y)
    {
        $values = [
            'focus_point_x' => MathUtility::forceIntegerInRange($x, -100, 100, 0),
            'focus_point_y' => MathUtility::forceIntegerInRange($y, -100, 100, 0),
        ];

        GeneralUtility::makeInstance(SysFileReferenceRepository::class)->update((int) $this->getReferenceUid(), $values);

        // save also to the file
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        $fileUid = $reference->getOriginalFile()->getUid();

        $sysFileMatadataRepository = GeneralUtility::makeInstance(SysFileMetadataRepository::class);
        $row = $sysFileMatadataRepository->findOneByFileUid((int) $fileUid);
        if ($row) {
            $sysFileMatadataRepository->update((int) $row['uid'], $values);
        }
    }

    /**
     * Fetch the meta data UID.
     *
     * @return int|null
     */
    protected function getReferenceUid()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return;
        }
        $p = $parameter['P'];
        if (isset($p['referenceUid']) && MathUtility::canBeInterpretedAsInteger($p['referenceUid'])) {
            return (int) $p['referenceUid'];
        }
    }
}
