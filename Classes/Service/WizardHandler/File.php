<?php


namespace HDNET\Focuspoint\Service\WizardHandler;


use HDNET\Focuspoint\Utility\FileUtility;
use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class File extends AbstractWizardHandler
{

    /**
     * Check if the handler can handle the current request
     *
     * @return true
     */
    public function canHandle()
    {
        $uid = $this->getMataDataUid();
        return $uid !== null;
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
                'metaUid' => $this->getMataDataUid()
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
        $row = GlobalUtility::getDatabaseConnection()
            ->exec_SELECTgetSingleRow('focus_point_x, focus_point_y', 'sys_file_metadata', 'uid=' . $this->getMataDataUid());
        return $this->cleanupPosition([
            $row['focus_point_x'],
            $row['focus_point_y']
        ]);
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
            ->exec_UPDATEquery('sys_file_metadata', 'uid=' . $this->getMataDataUid(), $values);
    }

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    public function getPublicUrl()
    {
        $fileObject = FileUtility::getFileByMetaData($this->getMataDataUid());
        return $fileObject->getPublicUrl(true);
    }

    /**
     * Fetch the meta data UID
     *
     * @return int|null
     */
    protected function getMataDataUid()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (isset($p['metaUid']) && MathUtility::canBeInterpretedAsInteger($p['metaUid'])) {
            return (int)$p['metaUid'];
        }
        if (isset($p['table']) && $p['table'] == 'sys_file_metadata' && isset($p['uid']) && MathUtility::canBeInterpretedAsInteger($p['uid'])) {
            return (int)$p['uid'];
        }
        return null;
    }
}