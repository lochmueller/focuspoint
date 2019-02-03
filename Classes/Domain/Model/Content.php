<?php

/**
 * Content.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * Content.
 *
 * @db     tt_content
 */
class Content extends AbstractModel
{
    /**
     * Image ratio.
     *
     * @var string
     * @db
     */
    protected $imageRatio;

    /**
     * Get image ratio.
     *
     * @return string
     */
    public function getImageRatio()
    {
        return $this->imageRatio;
    }

    /**
     * Set image ratio.
     *
     * @param string $imageRatio
     */
    public function setImageRatio($imageRatio)
    {
        $this->imageRatio = $imageRatio;
    }
}
