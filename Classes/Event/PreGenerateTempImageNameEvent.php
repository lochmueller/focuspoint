<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Event;

final class PreGenerateTempImageNameEvent
{
    public function __construct(
        protected string $name,
        protected string $absoluteImageName,
        protected string $ratio,
        protected int $focusPointX,
        protected int $focusPointY
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getAbsoluteImageName(): string
    {
        return $this->absoluteImageName;
    }

    public function getRatio(): string
    {
        return $this->ratio;
    }

    public function getFocusPointX(): int
    {
        return $this->focusPointX;
    }

    public function getFocusPointY(): int
    {
        return $this->focusPointY;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
