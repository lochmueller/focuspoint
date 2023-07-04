<?php

declare(strict_types=1);

use HDNET\Focuspoint\Domain\Model\Content;
use HDNET\Focuspoint\Domain\Model\FileMetadata;
use HDNET\Focuspoint\Domain\Model\FileReference;

return [
    Content::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'imageRatio' => [
                'fieldName' => 'image_ratio',
            ],
        ],
    ],
    FileMetadata::class => [
        'tableName' => 'sys_file_metadata',
        'properties' => [
            'focusPointY' => [
                'fieldName' => 'focus_point_y',
            ],
            'focusPointX' => [
                'fieldName' => 'focus_point_x',
            ],
        ],
    ],
    FileReference::class => [
        'tableName' => 'sys_file_reference',
        'properties' => [
            'focusPointY' => [
                'fieldName' => 'focus_point_y',
            ],
            'focusPointX' => [
                'fieldName' => 'focus_point_x',
            ],
        ],
    ],
];
