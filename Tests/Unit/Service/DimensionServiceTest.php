<?php

declare(strict_types=1);

/**
 * @todo    General file information
 */

namespace HDNET\Focuspoint\Tests\Unit\Service;

use HDNET\Focuspoint\Service\DimensionService;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @todo General class information
 *
 * @internal
 * @coversNothing
 */
final class DimensionServiceTest extends UnitTestCase
{
    /**
     * @var DimensionService
     */
    protected $service;

    /**
     * Build up.
     */
    protected function setUp(): void
    {
        $this->service = GeneralUtility::makeInstance(DimensionService::class);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidShortRatio(): void
    {
        $this->service->getRatio(1);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidLongRatio(): void
    {
        $this->service->getRatio('1:1:2');
    }

    public function testValidRatio(): void
    {
        static::assertSame([1, 1], $this->service->getRatio('1:1'));
        static::assertSame([16, 9], $this->service->getRatio('16:9'));
        static::assertSame([4, 3], $this->service->getRatio('4:3'));
    }

    /**
     * @return array
     */
    public function providerFocusBoxSize()
    {
        return [
            [
                100,
                0,
                '1:1',
                3644,
                2397,
                2397,
                2397,
            ],
        ];
    }

    /**
     * @dataProvider providerFocusBoxSize
     *
     * @param int    $focusX
     * @param int    $focusY
     * @param string $ratio
     * @param int    $imageWidth
     * @param int    $imageHeight
     * @param int    $expectedWidth
     * @param int    $expectedHeight
     */
    public function testFocusBoxSize(
        $focusX,
        $focusY,
        $ratio,
        $imageWidth,
        $imageHeight,
        $expectedWidth,
        $expectedHeight
    ): void {
        $expected = [
            $expectedWidth,
            $expectedHeight,
        ];

        static::assertSame($expected, $this->service->getFocusWidthAndHeight($imageWidth, $imageHeight, $ratio));
    }

    /**
     * @return array
     */
    public function providerFocusSourcePoint()
    {
        return [
            [
                100,
                0,
                '1:1',
                3644,
                2397,
                1247,
                0,
            ],
        ];
    }

    /**
     * @depends      testFocusBoxSize
     * @dataProvider providerFocusSourcePoint
     *
     * @param int    $focusX
     * @param int    $focusY
     * @param string $ratio
     * @param int    $imageWidth
     * @param int    $imageHeight
     * @param int    $expectedX
     * @param int    $expectedY
     */
    public function testFocusSourcePoint($focusX, $focusY, $ratio, $imageWidth, $imageHeight, $expectedX, $expectedY): void
    {
        $expected = [
            $expectedX,
            $expectedY,
        ];

        [$focusWidth, $focusHeight] = $this->service->getFocusWidthAndHeight($imageWidth, $imageHeight, $ratio);

        static::assertSame(
            $expected,
            $this->service->calculateSourcePosition(
                DimensionService::CROP_LANDSCAPE,
                $imageWidth,
                $imageHeight,
                $focusWidth,
                $focusHeight,
                $focusX,
                $focusY
            )
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Ratio have to be in the format of e.g. "1:1" or "16:9"
     */
    public function testRatioException(): void
    {
        $this->service->getFocusWidthAndHeight(100, 100, '11');
    }
}
