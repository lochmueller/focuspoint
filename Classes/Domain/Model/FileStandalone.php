<?php
/**
 * File standalone.
 *
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * File standalone.
 *
 * @db
 * @smartExclude EnableFields,Language
 */
class FileStandalone extends AbstractModel
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
     * @var string
     * @db
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
