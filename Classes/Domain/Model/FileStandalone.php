<?php

declare(strict_types=1);

/**
 * File standalone.
 */

namespace HDNET\Focuspoint\Domain\Model;

use HDNET\Autoloader\Annotation\DatabaseField;
use HDNET\Autoloader\Annotation\DatabaseTable;
use HDNET\Autoloader\Annotation\SmartExclude;

/**
 * File standalone.
 *
 * @DatabaseTable
 * @SmartExclude({"EnableFields", "Language"})
 */
class FileStandalone extends AbstractModel
{
    /**
     * Focus point Y.
     *
     * @var int
     * @DatabaseField(type="int")
     */
    protected $focusPointY;

    /**
     * Focus point X.
     *
     * @var int
     * @DatabaseField(type="int")
     */
    protected $focusPointX;

    /**
     * @var string
     * @DatabaseField(type="string")
     */
    protected $relativeFilePath;

    /**
     * Get Y.
     *
     * @return int
     */
    public function getFocusPointY()
    {
        return $this->focusPointY;
    }

    /**
     * Set Y.
     *
     * @param int $focusPointY
     */
    public function setFocusPointY($focusPointY): void
    {
        $this->focusPointY = $focusPointY;
    }

    /**
     * Get X.
     *
     * @return int
     */
    public function getFocusPointX()
    {
        return $this->focusPointX;
    }

    /**
     * Set X.
     *
     * @param int $focusPointX
     */
    public function setFocusPointX($focusPointX): void
    {
        $this->focusPointX = $focusPointX;
    }
}
