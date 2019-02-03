<?php

/**
 * File metadata.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * File metadata.
 *
 * @db     sys_file_metadata
 */
class FileMetadata extends AbstractModel
{
    /**
     * Focus point Y.
     *
     * @var int
     * @db
     */
    protected $focusPointY;

    /**
     * Focus point X.
     *
     * @var int
     * @db
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
    public function setFocusPointY($focusPointY)
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
    public function setFocusPointX($focusPointX)
    {
        $this->focusPointX = $focusPointX;
    }
}
