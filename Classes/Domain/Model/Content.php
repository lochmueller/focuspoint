<?php

/**
 * Content.
 */

namespace HDNET\Focuspoint\Domain\Model;

use HDNET\Autoloader\Annotation\DatabaseField;
use HDNET\Autoloader\Annotation\DatabaseTable;

/**
 * Content.
 *
 * @DatabaseTable("tt_content")
 */
class Content extends AbstractModel
{
    /**
     * Image ratio.
     *
     * @var string
     * @DatabaseField(type="string")
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
