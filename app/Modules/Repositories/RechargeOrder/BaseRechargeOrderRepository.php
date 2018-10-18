<?php

namespace App\Modules\Repositories\RechargeOrder;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Enums\RechargeOrderStatus;
use App\Modules\Models\ConsumeOrder\RechargeOrder;
use App\Modules\Repositories\BaseRepository;
use App\Modules\Services\Card\Facades\CardService;
use App\Modules\Services\Pay\Facades\Pay;

/**
 * Class BaseRechargeOrderRepository.
 */
class BaseRechargeOrderRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = RechargeOrder::class;


    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->with('goods');
    }

    /**
     * @param $input
     * @return RechargeOrder
     * @throws ApiException
     */
    public function create($input)
    {
        $card = CardService::getCardByInternalNumber($input['card_id']);
        $customer = CardService::getCustomerByCard($card);

        $input['card_id'] = $card->id;
        $input['customer_id'] = $customer->id;

        $rechargeOrder = $this->createRechargeOrderStub($input);
        if ($rechargeOrder->save())
        {
            return $rechargeOrder;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
    }

    /**
     * @param RechargeOrder $rechargeOrder
     * @param $barcode
     * @throws ApiException
     */
    public function pay(RechargeOrder $rechargeOrder, $barcode)
    {
        if (Pay::isWechatPay($barcode))
        {

        }
        else if (Pay::isAliPay($barcode))
        {

        }
        else
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }
    }

    /**
     * @param $input
     * @return RechargeOrder
     */
    private function createRechargeOrderStub($input)
    {
        $rechargeOrder = new RechargeOrder();
        $rechargeOrder->card_id = $input['card_id'];
        $rechargeOrder->customer_id = $input['customer_id'];
        $rechargeOrder->restaurant_id = $input['restaurant_id'];
        $rechargeOrder->restaurant_user_id = $input['restaurant_user_id'];
        $rechargeOrder->money = $input['money'];
        $rechargeOrder->pay_method = $input['money'];
        $rechargeOrder->status = RechargeOrderStatus::WAIT_PAY;

        if (isset($input['pay_method']) && $input['pay_method'] == PayMethodType::CASH)
        {
            $rechargeOrder->status = RechargeOrderStatus::COMPLETE;
        }

        return $rechargeOrder;
    }
}
