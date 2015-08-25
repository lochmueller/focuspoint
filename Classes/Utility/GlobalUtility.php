<?php
/**
 * Access global variables
 *
 * @package Focuspoint\Utility
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Access global variables
 *
 * @author Tim Lochmüller
 */
class GlobalUtility
{

    /**
     * Get database connection
     *
     * @return DatabaseConnection
     */
    public static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
