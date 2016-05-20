<?php


namespace HDNET\Focuspoint\Service\WizardHandler;


use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class FileReference extends AbstractWizardHandler
{

    /**
     * Check if the handler can handle the current request
     *
     * @return true
     */
    public function canHandle()
    {
        return $this->getReferenceUid() !== null;
    }

    /**
     * get the arguments for same request call
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            'P' => [
                'referenceUid' => $this->getReferenceUid()
            ],
        ];
    }

    /**
     * Return the current point
     *
     * @return array
     */
    public function getCurrentPoint()
    {
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        $properties = $reference->getProperties();
        return $this->cleanupPosition([
            $properties['focus_point_x'],
            $properties['focus_point_y']
        ]);
    }

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    public function getPublicUrl()
    {
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        return $this->displayableImageUrl($reference->getPublicUrl());
    }

    /**
     * Set the point (between -100 and 100)
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    public function setCurrentPoint($x, $y)
    {
        $values = [
            'focus_point_x' => MathUtility::forceIntegerInRange($x, -100, 100, 0),
            'focus_point_y' => MathUtility::forceIntegerInRange($y, -100, 100, 0)
        ];
        GlobalUtility::getDatabaseConnection()
            ->exec_UPDATEquery('sys_file_reference', 'uid=' . $this->getReferenceUid(), $values);

        // save also to the file
        $reference = ResourceFactory::getInstance()->getFileReferenceObject($this->getReferenceUid());
        $fileUid = $reference->getOriginalFile()->getUid();
        $row = GlobalUtility::getDatabaseConnection()
            ->exec_SELECTgetSingleRow('*', 'sys_file_metadata',
                'file=' . $fileUid);
        if ($row) {
            GlobalUtility::getDatabaseConnection()
                ->exec_UPDATEquery('sys_file_metadata', 'uid=' . $row['uid'], $values);
        }

    }

    /**
     * Fetch the meta data UID
     *
     * @return int|null
     */
    protected function getReferenceUid()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (isset($p['referenceUid']) && MathUtility::canBeInterpretedAsInteger($p['referenceUid'])) {
            return (int)$p['referenceUid'];
        }
        return null;
    }
}
