<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Stocks;

use App\Exceptions\Api\ApiException;
use Illuminate\Support\Facades\Auth;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\StockDetailStatus;
use App\Modules\Models\Stocks\Stocks;
use App\Modules\Models\Stocks\StocksDetail;
use App\Modules\Repositories\Stocks\BaseStocksRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Class StocksRepository
 * @package App\Repositories\Backend\Stocks
 */
class StocksRepository extends BaseStocksRepository
{

    //报损
    public function frmLoss(Stocks $stock, $input){
        //Log::info("stock update param:".json_encode($stock));
        //比较库存进行报损
        $count=$this->query()->where('id', $stock->id)->first()->count;
        //Log::info("count update param:".json_encode($count));
        $detail=$this->createStockDetailWithFRMLOSS($stock, $input,$count);
        try
        {
            DB::beginTransaction();
            if($count!=$input['count']){//库存和报损数字不同
                $detail->save();
                Log::info("detail update param:".json_encode($detail));
            }
            //修改库存表
            $stock->update($input);

            DB::commit();

            return $stock;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.stocks.update_error'));
        }
    }

    //采购加入
    public function purchase(Stocks $stock, $input){
        //Log::info("stock purchase param:".json_encode($stock));
        $detail=$this->createStockDetailWithPurchase($stock, $input);
        try
        {
            DB::beginTransaction();
            $detail->save();
            //Log::info("detail purchase param:".json_encode($detail));
            //修改库存表
            Log::info("stock purchase count1 param:".json_encode($input['count']));
            Log::info("stock purchase count2 param:".json_encode($stock->count));
            $input['count']=$input['count']+$stock->count;
            Log::info("stock purchase count3 param:".json_encode($input['count']));
            $stock->update($input);

            DB::commit();

            return $stock;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.stocks.update_error'));
        }
    }

    private function createStockDetailWithFRMLOSS(Stocks $stock,$input,$count){
        $user = Auth::User();
        $detail=new StocksDetail();
        $detail->material_id=$stock->material_id;
        if($count>$input['count']){//库存>输入数字,库存减少
            $detail->number=$count-$input['count'];
            $detail->status=StockDetailStatus::FRMLOSSMINUS;
        }else{//输入数字>库存,库存增加
            $detail->number=$input['count']-$count;
            $detail->status=StockDetailStatus::FRMLOSSPLUS;
        }
        $detail->restaurant_user_id=$user->id;
        $detail->shop_id=$user->shop_id;
        return $detail;
    }

    private function createStockDetailWithPurchase(Stocks $stock,$input){
        $user = Auth::User();
        $detail=new StocksDetail();
        $detail->material_id=$stock->material_id;
        $detail->number=$input['count'];
        $detail->status=StockDetailStatus::PURCHASE;
        $detail->restaurant_user_id=$user->id;
        $detail->shop_id=$user->shop_id;
        return $detail;
    }
}