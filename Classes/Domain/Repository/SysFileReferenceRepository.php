<?php

declare(strict_types=1);



namespace HDNET\Focuspoint\Domain\Repository;

class SysFileReferenceRepository extends AbstractRawRepository
{

    protected function getTableName(): string
    {
        return 'sys_file_reference';
    }
}
