<?php

declare(strict_types=1);

/**
 * File standalone.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * File standalone.
 */
class FileStandalone extends AbstractModel
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
     * @var string
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
