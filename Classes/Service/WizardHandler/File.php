<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Service\WizardHandler;

use HDNET\Focuspoint\Domain\Repository\SysFileMetadataRepository;
use HDNET\Focuspoint\Utility\FileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class File extends AbstractWizardHandler
{
    /**
     * Check if the handler can handle the current request.
     */
    public function canHandle(): bool
    {
        $uid = $this->getMataDataUid();

        return null !== $uid;
    }

    /**
     * get the arguments for same request call.
     */
    public function getArguments(): array
    {
        return [
            'P' => [
                'metaUid' => $this->getMataDataUid(),
            ],
        ];
    }

    /**
     * Return the current point.
     */
    public function getCurrentPoint(): array
    {
        $row = GeneralUtility::makeInstance(SysFileMetadataRepository::class)->findByUid((int) $this->getMataDataUid());

        return $this->cleanupPosition([
            $row['focus_point_x'] ?? 0,
            $row['focus_point_y'] ?? 0,
        ]);
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

        GeneralUtility::makeInstance(SysFileMetadataRepository::class)->update((int) $this->getMataDataUid(), $values);
    }

    /**
     * Get the public URL for the current handler.
     */
    public function getPublicUrl(): string
    {
        $fileObject = FileUtility::getFileByMetaData($this->getMataDataUid());

        return $this->displayableImageUrl($fileObject->getPublicUrl());
    }

    /**
     * Fetch the meta data UID.
     */
    protected function getMataDataUid(): ?int
    {
        $parameter = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (isset($p['metaUid']) && MathUtility::canBeInterpretedAsInteger($p['metaUid'])) {
            return (int) $p['metaUid'];
        }
        if (isset($p['table']) && 'sys_file_metadata' === $p['table'] && isset($p['uid']) && MathUtility::canBeInterpretedAsInteger($p['uid'])) {
            return (int) $p['uid'];
        }

        return null;
    }
}
