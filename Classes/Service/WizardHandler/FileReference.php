<?php

declare(strict_types = 1);

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
     */
    public function canHandle(): bool
    {
        return null !== $this->getReferenceUid();
    }

    /**
     * get the arguments for same request call.
     */
    public function getArguments(): array
    {
        return [
            'P' => [
                'referenceUid' => $this->getReferenceUid(),
            ],
        ];
    }

    /**
     * Return the current point.
     */
    public function getCurrentPoint(): array
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
     */
    public function getPublicUrl(): string
    {
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());

        return $this->displayableImageUrl($reference->getPublicUrl());
    }

    /**
     * Set the point (between -100 and 100).
     */
    public function setCurrentPoint(int $x, int $y): void
    {
        $values = [
            'focus_point_x' => MathUtility::forceIntegerInRange($x, -100, 100, 0),
            'focus_point_y' => MathUtility::forceIntegerInRange($y, -100, 100, 0),
        ];

        GeneralUtility::makeInstance(SysFileReferenceRepository::class)->update((int)$this->getReferenceUid(), $values);

        // save also to the file
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        $fileUid = $reference->getOriginalFile()->getUid();

        $sysFileMatadataRepository = GeneralUtility::makeInstance(SysFileMetadataRepository::class);
        $row = $sysFileMatadataRepository->findOneByFileUid((int)$fileUid);
        if ($row && 0 === (int)$row['focus_point_y'] && 0 === (int)$row['focus_point_x']) {
            $sysFileMatadataRepository->update((int)$row['uid'], $values);
        }
    }

    /**
     * Fetch the meta data UID.
     */
    protected function getReferenceUid(): ?int
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (isset($p['referenceUid']) && MathUtility::canBeInterpretedAsInteger($p['referenceUid'])) {
            return (int)$p['referenceUid'];
        }
    }
}
