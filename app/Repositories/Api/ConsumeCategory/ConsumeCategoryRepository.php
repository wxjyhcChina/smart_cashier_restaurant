<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeCategory;

use App\Modules\Repositories\ConsumeCategory\BaseConsumeCategoryRepository;

/**
 * Class ConsumeCategoryRepository
 * @package App\Repositories\Backend\ConsumeCategory
 */
class ConsumeCategoryRepository extends BaseConsumeCategoryRepository
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