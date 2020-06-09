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
        return '<a href="' . route('admin.stocks.edit', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }


    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
        return '<a href="' . route('admin.stocks.purchaseInfo', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-bars" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
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
            $this->edit_button;
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
