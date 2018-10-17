<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeOrder;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Repositories\ConsumeOrder\BaseConsumeOrderRepository;
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
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->get();
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

        $goods['labelId'] = $label->id;
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

        $excludeGoods = $this->excludeGoods($restaurant_id, $dinningTime->id);

        $price = 0;
        $goodsArray = [];
        foreach ($labels as $labelId)
        {
            $goods = $this->getLabelCategoryGoods($restaurant_id, $labelId, $dinningTime->id);

            $foundGoods = $excludeGoods->where('id', $goods->id)->first();

            if ($foundGoods == null)
            {
                $price = bcadd($price, $goods->price, 2);
                array_push($goodsArray, $goods);
            }
        }

        foreach ($tempGoods as $goodsId)
        {
            $goods = $this->getGoods($restaurant_id, $goodsId);
            $foundGoods = $excludeGoods->where('id', $goods->id)->first();

            if ($foundGoods == null)
            {
                $goods['labelId'] = null;
                array_push($goodsArray, $goods);
            }
        }

        if ($forceDiscount != null)
        {
            $discountPrice = bcmul($price, $forceDiscount, 2);
        }

        $response = [];
        $response['goods_count'] = count($goodsArray);
        $response['dinning_time_id'] = $dinningTime->id;
        $response['price'] = $price;
        $response['discount'] = $forceDiscount;
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
            $consumeOrder->restaurant_id = $restaurant_id;
            $consumeOrder->restaurant_user_id = $restaurant_user_id;
            $consumeOrder->dinning_time_id = $response['dinning_time_id'];
            $consumeOrder->price = $response['price'];
            $consumeOrder->discount_price = $response['discount_price'];
            $consumeOrder->discount = $response['discount'];
            $consumeOrder->goods_count = $response['goods_count'];
            $consumeOrder->status = ConsumeOrderStatus::WAIT_PAY;
            $consumeOrder->save();

            $goodsIds = [];
            foreach ($response['goods'] as $goods)
            {
                $goodsIds[$goods->id] = ['label_id' => $goods['labelId']];
            }
            $consumeOrder->goods()->attach($goodsIds);

            DB::commit();

            return $consumeOrder->load('goods');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
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
    private function excludeGoods($restaurant_id, $dinning_time_id)
    {
        $now = Carbon::now();
        $excludeTime = Carbon::now()->subSeconds(config('constants.order.exclude_time'));

        $order = ConsumeOrder::where('restaurant_id', $restaurant_id)
            ->where('dinning_time_id', $dinning_time_id)
            ->whereBetween('updated_at', [$excludeTime, $now])
            ->orderBy('id', 'desc')
            ->first();

        $goods = collect();
        if ($order != null)
        {
            $goods = $order->goods;
        }

        return $goods;
    }
}