<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\VersionAndroid;

use App\Modules\Repositories\VersionAndroid\BaseVersionAndroidRepository;

/**
 * Class VersionAndroidRepository
 * @package App\Repositories\Backend\VersionAndroid
 */
class VersionAndroidRepository extends BaseVersionAndroidRepository
{
    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query();
    }
}