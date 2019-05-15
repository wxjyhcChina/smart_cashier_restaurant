<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Card;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;
use App\Modules\Repositories\Card\BaseCardRepository;

/**
 * Class CardRepository
 * @package App\Repositories\Backend\Card
 */
class CardRepository extends BaseCardRepository
{
    /**
     * @param $conditions
     * @return  Card
     * @throws ApiException
     */
    public function findOne($conditions)
    {
        $conditionCollection = collect($conditions);
        $conditionCollection = $conditionCollection->only(['id', 'restaurant_id', 'customer_id', 'number', 'internal_number', 'customer_id', 'status', 'enabled']);

        $card = $this->query()
            ->where($conditionCollection->toArray())
            ->with('customer')
            ->first();

        if ($card == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.invalid_card'), 404);
        }

        return $card;
    }
}