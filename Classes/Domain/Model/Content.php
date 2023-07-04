<?php

declare(strict_types=1);

/**
 * Content.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * Content.
 */
class Content extends AbstractModel
{
    /**
     * Image ratio.
     *
     * @var string
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
    public function setImageRatio($imageRatio): void
    {
        $this->imageRatio = $imageRatio;
    }
}
