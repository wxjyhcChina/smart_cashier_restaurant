<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\ConsumeRule;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Repositories\ConsumeRule\BaseConsumeRuleRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class ConsumeRuleRepository extends BaseConsumeRuleRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->with('dinning_time')->with('consume_categories');
    }

    /**
     * @param $shop_id
     * @return mixed
     */
    public function getByShopWithRelationQuery($shop_id)
    {
        return $this->getByShopQuery($shop_id)->with('dinning_time')->with('consume_categories');
    }

    /**
     * @param ConsumeRule $consumeRule
     * @param $status
     * @return bool
     * @throws ApiException
     */
    public function mark(ConsumeRule $consumeRule, $status)
    {
        $consumeRule->enabled = $status;

        if ($consumeRule->save()) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.department.mark_error'));
    }
}