<?php

namespace App\Modules\Models\Stocks;

use App\Modules\Enums\StockDetailStatus;
use Illuminate\Database\Eloquent\Model;

class StocksDetail extends Model
{
    protected $table = 'stocks_detail';

    protected $fillable = ['id', 'material_id','number', 'status', 'restaurant_user_id', 'shop_id', 'mch_private_key_path'];

    public function getShowStatusAttribute()
    {
        switch ($this->status)
        {
            case StockDetailStatus::CONSUME:
                $ret = trans('api.stock.status.consume');
                break;
            case StockDetailStatus::FRMLOSSPLUS:
                $ret = trans('api.stock.status.frmlossplus');
                break;
            case StockDetailStatus::FRMLOSSMINUS:
                $ret = trans('api.stock.status.frmlossminus');
                break;
            case StockDetailStatus::EXPEND:
                $ret = trans('api.stock.status.expend');
                break;
            case StockDetailStatus::PURCHASE:
                $ret = trans('api.stock.status.purchase');
                break;
            default:
                $ret = '';
        }

        return $ret;
    }
}