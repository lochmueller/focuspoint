<?php
/**
 * Access global variables.
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Access global variables.
 */
class GlobalUtility
{
    /**
     * Get database connection.
     *
     * @return DatabaseConnection
     */
    public static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
