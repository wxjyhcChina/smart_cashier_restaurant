<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Department;

use App\Modules\Repositories\Department\BaseDepartmentRepository;

/**
 * Class DepartmentRepository
 * @package App\Repositories\Backend\Department
 */
class DepartmentRepository extends BaseDepartmentRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->get();
    }
}