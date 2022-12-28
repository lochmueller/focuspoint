<?php

declare(strict_types=1);

/**
 * File metadata.
 */

namespace HDNET\Focuspoint\Domain\Model;

use HDNET\Autoloader\Annotation\DatabaseField;
use HDNET\Autoloader\Annotation\DatabaseTable;

/**
 * File metadata.
 *
 * @DatabaseTable("sys_file_metadata")
 */
class FileMetadata extends AbstractModel
{
    /**
     * Focus point Y.
     *
     * @var int
     *
     * @DatabaseField(type="int")
     */
    protected $focusPointY;

    /**
     * Focus point X.
     *
     * @var int
     *
     * @DatabaseField(type="int")
     */
    protected $focusPointX;

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
