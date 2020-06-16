<?php

namespace App\Modules\Models\Stocks\Traits\Attribute;

/**
 * Trait StocksAttribute
 * @package App\Modules\Models\Stocks\Traits\Attribute
 */
trait StocksAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.stocks.edit', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }


    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
        return '<a href="' . route('admin.stocks.purchaseInfo', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-bars" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDailyConsumeButtonAttribute()
    {
        return '<a href="' . route('admin.stocks.stockConsume', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-bars" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.stocks.StockConsume') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->status_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->edit_button.
            $this->daily_consume_button ;
    }

    /**
     * @return string
     */
    public function getStatusActionButtonsAttribute()
    {
        return
            $this->status_button;
    }
}
