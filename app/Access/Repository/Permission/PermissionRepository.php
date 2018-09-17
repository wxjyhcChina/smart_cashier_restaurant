<?php

namespace App\Access\Repository\Permission;

use App\Repositories\BaseRepository;
use App\Access\Model\Permission\Permission;

/**
 * Class PermissionRepository.
 */
class PermissionRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Permission::class;
}
