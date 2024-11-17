<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Event;

final class PostCroppedImageSrcBySrcEvent
{
    public function __construct(protected string $tempImageName) {}

    public function getTempImageName(): string
    {
        return $this->tempImageName;
    }

    public function setTempImageName(string $tempImageName): void
    {
        $this->tempImageName = $tempImageName;
    }
}
