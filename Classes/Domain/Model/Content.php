<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Domain\Model;

class Content extends AbstractModel
{
    protected string $imageRatio = '';

    public function getImageRatio(): string
    {
        return $this->imageRatio;
    }

    public function setImageRatio(string $imageRatio): void
    {
        $this->imageRatio = $imageRatio;
    }
}
