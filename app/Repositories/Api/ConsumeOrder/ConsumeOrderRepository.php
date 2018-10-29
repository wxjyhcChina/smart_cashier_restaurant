<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeOrder;

use App\Common\Util\OrderUtil;
use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Repositories\ConsumeOrder\BaseConsumeOrderRepository;
use App\Modules\Services\Account\Facades\Account;
use App\Modules\Services\Card\Facades\CardService;
use App\Modules\Services\Pay\Facades\Pay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ConsumeOrderRepository
 * @package App\Repositories\Backend\ConsumeOrder
 */
class ConsumeOrderRepository extends BaseConsumeOrderRepository
{
    /**
     * @param $restaurant_id
     * @param $input
     * @return mixed
     */
    public function getByRestaurant($restaurant_id, $input)
    {
        $query = $this->getByRestaurantQuery($restaurant_id);
        if (isset($input['start_time']) && isset($input['end_time']))
        {
            $query = $query->whereBetween('created_at', [$input['start_time'].' 00:00:00', $input['end_time']." 23:59:59"]);
        }

        return $query->paginate(15);
    }

    /**
     * @param $restaurant_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws ApiException
     */
    private function getCurrentDinningTime($restaurant_id)
    {
        $current_time = Carbon::now()->format("H:i");

        $dinningTime = DinningTime::where('enabled', 1)
            ->where('start_time', '<=' , $current_time)
            ->where('end_time', '>', $current_time)
            ->where('restaurant_id', $restaurant_id)
            ->first();

        if ($dinningTime == null)
        {
            throw new ApiException(ErrorCode::NOT_IN_DINNING_TIME, trans('api.error.not_in_dinning_time'));
        }

        return $dinningTime;
    }

    /**
     * @param $restaurant_id
     * @param $labelId
     * @param $dinningTimeId
     * @return mixed
     * @throws ApiException
     */
    private function getLabelCategoryGoods($restaurant_id, $labelId, $dinningTimeId)
    {
        $label = Label::where('rfid', $labelId)
            ->first();

        if ($label == null)
        {
            throw new ApiException(ErrorCode::LABEL_NOT_EXIST, trans('api.error.label_not_exist'));
        }

        $labelCategory = $label->labelCategory()
            ->where('restaurant_id', $restaurant_id)
            ->first();
        if ($labelCategory == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BINDED, trans('api.error.label_category_not_binded'));
        }

        $goods = $labelCategory->goods()->where('dinning_time_id', $dinningTimeId)->first();
        if ($goods == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BIND_GOOD, trans('api.error.label_category_not_bind_good'));
        }

        $goods->label_id = $label->id;
        return $goods;
    }


    /**
     * @param $restaurant_id
     * @param $goodsId
     * @return mixed|static
     * @throws ApiException
     */
    private function getGoods($restaurant_id, $goodsId)
    {
        $goods = Goods::where('restaurant_id', $restaurant_id)
            ->where('id', $goodsId)
            ->first();

        if ($goods == null)
        {
            throw new ApiException(ErrorCode::GOODS_NOT_EXIST, $goodsId.trans('api.error.goods_not_exist'));
        }

        return $goods;
    }

    /**
     * @param $restaurant_id
     * @param $labels
     * @param $tempGoods
     * @param $forceDiscount
     * @return array
     * @throws ApiException
     */
    private function getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount)
    {
        $dinningTime = $this->getCurrentDinningTime($restaurant_id);

        $excludeLabels = $this->excludeLabels($restaurant_id, $dinningTime->id);

        $price = 0;
        $goodsArray = [];
        foreach ($labels as $labelId)
        {
            $goods = $this->getLabelCategoryGoods($restaurant_id, $labelId, $dinningTime->id);

            $foundLabel = $excludeLabels->where('id', $goods->labelId)->first();

            if ($foundLabel == null)
            {
                $price = bcadd($price, $goods->price, 2);
                array_push($goodsArray, $goods);
            }
        }

        foreach ($tempGoods as $goodsId)
        {
            $goods = $this->getGoods($restaurant_id, $goodsId);

            $price = bcadd($price, $goods->price, 2);
            $goods->label_id = null;
            array_push($goodsArray, $goods);
        }

        $discountPrice = $price;
        if ($forceDiscount != null)
        {
            $discountPrice = bcmul($price, bcdiv($forceDiscount, 10, 2), 2);
        }

        if (count($goodsArray) == 0)
        {
            throw  new ApiException(ErrorCode::ORDER_GOODS_NOT_EXIST, trans('api.error.order_goods_not_exist'));
        }

        $response = [];
        $response['goods_count'] = count($goodsArray);
        $response['dinning_time_id'] = $dinningTime->id;
        $response['price'] = $price;
        $response['force_discount'] = $forceDiscount;
        $response['discount_price'] = $discountPrice;
        $response['goods'] = $goodsArray;

        return $response;
    }

    /**
     * @param $input
     * @return array
     * @throws ApiException
     */
    public function preCreate($input)
    {
        $labels = isset($input['labels']) ? $input['labels'] : array();
        $tempGoods = isset($input['temp_goods']) ? $input['temp_goods'] : array();

        $forceDiscount = isset($input['discount']) ? $input['discount'] : null;
        $restaurant_id = $input['restaurant_id'];

        return $this->getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount);
    }

    /**
     * @param $input
     * @return ConsumeOrder
     * @throws ApiException
     */
    public function create($input)
    {
        $labels = isset($input['labels']) ? $input['labels'] : array();
        $tempGoods = isset($input['temp_goods']) ? $input['temp_goods'] : array();

        $forceDiscount = isset($input['discount']) ? $input['discount'] : null;
        $restaurant_id = $input['restaurant_id'];
        $restaurant_user_id = $input['restaurant_user_id'];

        $response = $this->getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount);

        try
        {
            DB::beginTransaction();

            $consumeOrder = new ConsumeOrder();
            $consumeOrder->order_id = OrderUtil::generateConsumeOrderId();
            $consumeOrder->restaurant_id = $restaurant_id;
            $consumeOrder->restaurant_user_id = $restaurant_user_id;
            $consumeOrder->dinning_time_id = $response['dinning_time_id'];
            $consumeOrder->price = $response['price'];
            $consumeOrder->discount_price = $response['discount_price'];
            $consumeOrder->force_discount = $response['force_discount'];
            $consumeOrder->goods_count = $response['goods_count'];
            $consumeOrder->status = ConsumeOrderStatus::WAIT_PAY;
            $consumeOrder->save();

            $goodsIds = [];
            foreach ($response['goods'] as $goods)
            {
                $goodsIds[$goods->id] = ['label_id' => $goods->label_id];
            }
            $consumeOrder->goods()->attach($goodsIds);

            DB::commit();

            return $consumeOrder->load('goods', 'customer', 'card');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            throw $exception;
//            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
        }
    }

    /**
     * @param $restaurant_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function latestOrder($restaurant_id)
    {
        $order = ConsumeOrder::where('restaurant_id', $restaurant_id)
            ->orderBy('id', 'desc')
            ->with('goods')
            ->first();

        return $order;
    }

    /**
     * @param $restaurant_id
     * @param $dinning_time_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    private function excludeLabels($restaurant_id, $dinning_time_id)
    {
        $now = Carbon::now();
        $excludeTime = Carbon::now()->subSeconds(config('constants.order.exclude_time'));

        $order = ConsumeOrder::where('restaurant_id', $restaurant_id)
            ->where('dinning_time_id', $dinning_time_id)
            ->whereBetween('updated_at', [$excludeTime, $now])
            ->orderBy('id', 'desc')
            ->first();

        $labels = collect();
        if ($order != null)
        {
            $labels = $order->labels;
        }

        return $labels;
    }

    /**
     * @param ConsumeOrder $order
     * @return ConsumeOrder
     */
    private function payWithCash(ConsumeOrder $order)
    {
        $order->pay_method = PayMethodType::CASH;
        $order->status = ConsumeOrderStatus::COMPLETE;
        $order->save();

        return $order;
    }

    /**
     * @param $order
     * @param $customer
     * @return mixed|null
     */
    private function getCardDiscount($order, $customer)
    {
        $discount = null;
        if ($order->force_discount == null && $customer->consume_category_id != null)
        {
            $weekday = pow(2, Carbon::parse($order->created_at)->dayOfWeek);

            //calculate discount
            $rule = ConsumeRule::whereHas('dinning_time', function ($query) use ($order){
                $query->where('dinning_time.id', $order->dinning_time_id);
            })->whereHas('consume_categories', function ($query) use ($customer){
                $query->where('consume_categories.id', $customer->consume_category_id);
            })->whereRaw('weekday & '.$weekday.' > 0')->first();

            if ($rule != null)
            {
                $discount = $rule->discount;
            }
        }

        return $discount;
    }


    /**
     * @param ConsumeOrder $order
     * @param $cardId
     * @return ConsumeOrder
     * @throws ApiException
     */
    private function payWithCard(ConsumeOrder $order, $cardId)
    {
        $card = CardService::getCardByInternalNumber($cardId);

        $customer = CardService::getCustomerByCard($card);

        $discount = $this->getCardDiscount($order, $customer);

        //check account balance
        $account = $customer->account;
        $original_price = $order->price;
        $discount_price = $original_price;

        $orderDiscount = $order->force_discount;
        if ($orderDiscount == null)
        {
            $orderDiscount = $discount;
        }

        if ($orderDiscount != null)
        {
            $discount_price = bcmul($original_price, bcdiv($orderDiscount, 10, 2), 2);
        }

        Account::compareBalance($account, $discount_price);

        try
        {
            DB::beginTransaction();

            //add account record
            Account::payAccount($order->id, $account, $discount_price);

            //modify order status
            $order->customer_id = $customer->id;
            $order->card_id = $card->id;
            $order->department_id = $customer->department_id;
            $order->consume_category_id = $customer->consume_category_id;
            $order->discount_price = $discount_price;
            $order->discount = $discount;
            $order->pay_method = PayMethodType::CARD;
            $order->status = ConsumeOrderStatus::COMPLETE;
            $order->save();

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
        }

        return $order->load('customer');
    }

    /**
     * @param ConsumeOrder $order
     * @param $barcode
     * @return ConsumeOrder
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function payWithBarcode(ConsumeOrder $order, $barcode)
    {
        //check alipay code or wechat code
        if (Pay::isWechatPay($barcode))
        {
            $response = Pay::barcodeWechatPay($order->order_id, $barcode, $order->price, '消费');
        }
        else if (Pay::isAliPay($barcode))
        {
            $response = Pay::barcodeAlipay($order->order_id, $barcode, $order->price, '消费');
        }
        else
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        if ($response->getTradeStatus() == "SUCCESS")
        {
            $this->paySuccess($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY, $response->getTradeNo());
            return $order;
        }
        else if ($response->getTradeStatus() == "CLOSED")
        {
            $this->payClosed($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::RECHARGE_ORDER_CANCELED, trans('api.error.recharge_order_canceled'));
        }
        else
        {
            $this->payClosed($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::PAY_FAILED, $response->getErrorMessage());
        }
    }

    /**
     * @param ConsumeOrder $order
     * @param $pay_method
     * @param $trade_no
     */
    public function paySuccess(ConsumeOrder $order, $pay_method, $trade_no=null)
    {
        $order->status = ConsumeOrderStatus::COMPLETE;
        $order->pay_method = $pay_method;
        $order->external_pay_no = $trade_no;
        $order->save();
    }

    /**
     * @param ConsumeOrder $order
     * @param $pay_method
     */
    public function payClosed(ConsumeOrder $order, $pay_method)
    {
        $order->status = ConsumeOrderStatus::CLOSED;
        $order->pay_method = $pay_method;
        $order->save();
    }

    /**
     * @param ConsumeOrder $order
     * @param $input
     * @return ConsumeOrder
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function pay(ConsumeOrder $order, $input)
    {
        if ($order->status != ConsumeOrderStatus::WAIT_PAY)
        {
            throw  new ApiException(ErrorCode::ORDER_STATUS_INCORRECT, trans('api.error.order_status_incorrect'));
        }

        if (isset($input['pay_method']))
        {
            $payMethod = $input['pay_method'];
            $method = PayMethod::where('method', $payMethod)->where('enabled', 1)->first();
            if ($method == null)
            {
                throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
            }

            if ($payMethod == PayMethodType::CASH)
            {
                $order = $this->payWithCash($order);
            }
            else if ($payMethod == PayMethodType::CARD)
            {
                if (!isset($input['card_id']))
                {
                    throw new ApiException(ErrorCode::INPUT_INCOMPLETE, trans('api.error.input_incomplete'));
                }

                $order = $this->payWithCard($order, $input['card_id']);
            }
        }
        else
        {
            $order = $this->payWithBarcode($order, $input['barcode']);
        }

        return $order->load('goods', 'customer', 'card');
    }
}