<?php

declare(strict_types = 1);

/**
 * SysFileReference.
 */

namespace HDNET\Focuspoint\Domain\Repository;

/**
 *  SysFileReference.
 */
class SysFileReferenceRepository extends AbstractRawRepository
{
    /**
     * Get the tablename.
     */
    protected function getTableName(): string
    {
        return 'sys_file_reference';
    }
}
