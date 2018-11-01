<?php

namespace App\Modules\Repositories\RechargeOrder;

use App\Common\Util\OrderUtil;
use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Enums\RechargeOrderStatus;
use App\Modules\Models\RechargeOrder\RechargeOrder;
use App\Modules\Repositories\BaseRepository;
use App\Modules\Services\Account\Facades\Account;
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
            ->where('recharge_orders.restaurant_id', $restaurant_id);
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
            if (isset($input['pay_method']) && $input['pay_method'] == PayMethodType::CASH)
            {
                $this->paySuccess($rechargeOrder, PayMethodType::CASH);
            }

            return $rechargeOrder;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
    }

    /**
     * @param RechargeOrder $rechargeOrder
     * @param $barcode
     * @return RechargeOrder
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function pay(RechargeOrder $rechargeOrder, $barcode)
    {
        if (Pay::isWechatPay($barcode))
        {
            $response = Pay::barcodeWechatPay($rechargeOrder->order_id, $barcode, $rechargeOrder->money, '充值');
        }
        else if (Pay::isAliPay($barcode))
        {
            $response = Pay::barcodeAlipay($rechargeOrder->order_id, $barcode, $rechargeOrder->money, '充值');
        }
        else
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        if ($response->getTradeStatus() == "SUCCESS")
        {
            $this->paySuccess($rechargeOrder, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY, $response->getTradeNo());
            return $rechargeOrder;
        }
        else if ($response->getTradeStatus() == "CLOSED")
        {
            $this->payClosed($rechargeOrder, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::RECHARGE_ORDER_CANCELED, trans('api.error.recharge_order_canceled'));
        }
        else
        {
            $this->payClosed($rechargeOrder, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::PAY_FAILED, $response->getErrorMessage());
        }
    }

    /**
     * @param RechargeOrder $rechargeOrder
     * @param $pay_method
     * @param $trade_no
     */
    public function paySuccess(RechargeOrder $rechargeOrder, $pay_method, $trade_no=null)
    {
        $rechargeOrder->status = RechargeOrderStatus::COMPLETE;
        $rechargeOrder->pay_method = $pay_method;
        $rechargeOrder->external_pay_no = $trade_no;
        $rechargeOrder->save();

        Account::rechargeAccount($rechargeOrder->id, $rechargeOrder->customer->account, $rechargeOrder->money, $pay_method);
    }

    /**
     * @param RechargeOrder $rechargeOrder
     * @param $pay_method
     */
    public function payClosed(RechargeOrder $rechargeOrder, $pay_method)
    {
        $rechargeOrder->status = RechargeOrderStatus::CLOSED;
        $rechargeOrder->pay_method = $pay_method;
        $rechargeOrder->save();
    }

    /**
     * @param $input
     * @return RechargeOrder
     */
    private function createRechargeOrderStub($input)
    {
        $rechargeOrder = new RechargeOrder();
        $rechargeOrder->order_id = OrderUtil::generateRechargeOrderId();
        $rechargeOrder->card_id = $input['card_id'];
        $rechargeOrder->customer_id = $input['customer_id'];
        $rechargeOrder->restaurant_id = $input['restaurant_id'];
        $rechargeOrder->restaurant_user_id = $input['restaurant_user_id'];
        $rechargeOrder->money = $input['money'];
        $rechargeOrder->pay_method = isset($input['pay_method']) ? $input['pay_method'] : '';
        $rechargeOrder->status = RechargeOrderStatus::WAIT_PAY;

        return $rechargeOrder;
    }
}
