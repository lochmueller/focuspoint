<?php

/**
 * SysFileReference
 *
 */

namespace HDNET\Focuspoint\Domain\Repository;

/**
 *  SysFileReference
 */
class SysFileReferenceRepository extends AbstractRawRepository
{

    /**
     * Get the tablename
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return 'sys_file_reference';
    }
}
