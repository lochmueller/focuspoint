<?php

declare(strict_types=1);

/**
 * File metadata.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * File metadata.
 */
class FileReference extends AbstractModel
{
    /**
     * Focus point Y.
     *
     * @var int
     */
    protected $focusPointY;

    /**
     * Focus point X.
     *
     * @var int
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
