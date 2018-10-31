<?php

namespace App\Modules\Models\ConsumeOrder\Traits\Attribute;

/**
 * Trait ConsumeOrderAttribute
 * @package App\Modules\Models\ConsumeOrder\Traits\Attribute
 */
trait ConsumeOrderAttribute
{
    /**
     * @return string
     */
    public function getInfoButtonAttribute()
    {
        return '<a href="' . route('admin.consumeOrder.info', $this, false) . '" class="btn btn-xs btn-info"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.info') . '"></i></a> ';
    }


    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->info_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->info_button;
    }
}
