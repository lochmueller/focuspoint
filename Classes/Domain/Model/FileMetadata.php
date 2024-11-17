<?php

declare(strict_types=1);



namespace HDNET\Focuspoint\Domain\Model;


class FileMetadata extends AbstractModel
{
    protected int $focusPointY = 0;

    protected int $focusPointX = 0;

    public function getFocusPointY():int
    {
        return $this->focusPointY;
    }

    public function setFocusPointY(int $focusPointY): void
    {
        $this->focusPointY = $focusPointY;
    }

    public function getFocusPointX():int
    {
        return $this->focusPointX;
    }

    public function setFocusPointX(int $focusPointX): void
    {
        $this->focusPointX = $focusPointX;
    }
}
