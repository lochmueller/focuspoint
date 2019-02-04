<?php

namespace HDNET\Focuspoint\Service\WizardHandler;

use HDNET\Focuspoint\Domain\Repository\SysFileMetadataRepository;
use HDNET\Focuspoint\Utility\FileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * File.
 */
class File extends AbstractWizardHandler
{
    /**
     * Check if the handler can handle the current request.
     *
     * @return bool
     */
    public function canHandle()
    {
        $uid = $this->getMataDataUid();

        return null !== $uid;
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
                'metaUid' => $this->getMataDataUid(),
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
        $row = GeneralUtility::makeInstance(SysFileMetadataRepository::class)->findByUid((int) $this->getMataDataUid());

        return $this->cleanupPosition([
            $row['focus_point_x'],
            $row['focus_point_y'],
        ]);
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

        GeneralUtility::makeInstance(SysFileMetadataRepository::class)->update((int) $this->getMataDataUid(), $values);
    }

    /**
     * Get the public URL for the current handler.
     *
     * @return string
     */
    public function getPublicUrl()
    {
        $fileObject = FileUtility::getFileByMetaData($this->getMataDataUid());

        return $this->displayableImageUrl($fileObject->getPublicUrl());
    }

    /**
     * Fetch the meta data UID.
     *
     * @return int|null
     */
    protected function getMataDataUid()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return;
        }
        $p = $parameter['P'];
        if (isset($p['metaUid']) && MathUtility::canBeInterpretedAsInteger($p['metaUid'])) {
            return (int) $p['metaUid'];
        }
        if (isset($p['table']) && 'sys_file_metadata' === $p['table'] && isset($p['uid']) && MathUtility::canBeInterpretedAsInteger($p['uid'])) {
            return (int) $p['uid'];
        }
    }
}
