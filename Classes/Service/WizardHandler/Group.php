<?php


namespace HDNET\Focuspoint\Service\WizardHandler;

use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Group extends AbstractWizardHandler
{

    /**
     * The table name
     */
    const TABLE = 'tx_focuspoint_domain_model_filestandalone';

    /**
     * Check if the handler can handle the current request
     *
     * @return boolean
     */
    public function canHandle()
    {
        return $this->getRelativeFilePath() !== null;
    }

    /**
     * Return the current point
     *
     * @return integer[]
     */
    public function getCurrentPoint()
    {
        $connection = GlobalUtility::getDatabaseConnection();
        $row = $connection->exec_SELECTgetSingleRow(
            'uid,focus_point_x,focus_point_y',
            self::TABLE,
            'relative_file_path = ' . $connection->fullQuoteStr($this->getRelativeFilePath(), self::TABLE)
        );

        if ($row === false) {
            return [0, 0];
        }
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
        $connection = GlobalUtility::getDatabaseConnection();
        $row = $connection->exec_SELECTgetSingleRow(
            'uid',
            self::TABLE,
            'relative_file_path = ' . $connection->fullQuoteStr($this->getRelativeFilePath(), self::TABLE)
        );
        $values = [
            'focus_point_x' => $x,
            'focus_point_y' => $y,
            'relative_file_path' => $this->getRelativeFilePath()
        ];
        if ($row) {
            $connection->exec_UPDATEquery(self::TABLE, 'uid=' . $row['uid'], $values);
        } else {
            $connection->exec_INSERTquery(self::TABLE, $values);
        }
    }

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->displayableImageUrl($this->getRelativeFilePath());
    }

    /**
     * get the arguments for same request call
     *
     * @return array
     */
    public function getArguments()
    {
        $parameter = GeneralUtility::_GET();
        $p = $parameter['P'];
        return [
            'P' => [
                'table' => $p['table'],
                'field' => $p['field'],
                'file' => $p['file'],
            ],
        ];
    }

    /**
     * get the file name
     *
     * @return null|string
     */
    protected function getRelativeFilePath()
    {
        $parameter = GeneralUtility::_GET();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (!isset($p['table']) || !isset($p['field']) || !isset($p['file'])) {
            return null;
        }
        if (!isset($GLOBALS['TCA'][$p['table']])) {
            return null;
        }
        $tableTca = $GLOBALS['TCA'][$p['table']];
        if (!isset($tableTca['columns'][$p['field']])) {
            return null;
        }
        $fieldTca = $tableTca['columns'][$p['field']];

        $filePath = rtrim($fieldTca['config']['uploadfolder'], '/') . '/' . $p['file'];
        if (!is_file(GeneralUtility::getFileAbsFileName($filePath))) {
            return null;
        }
        return $filePath;
    }
}
