<?php

namespace App\Modules\Models\ConsumeCategory\Traits\Attribute;

/**
 * Trait ConsumeCategoryAttribute
 * @package App\Modules\Models\ConsumeCategory\Traits\Attribute
 */
trait ConsumeCategoryAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.consumeCategory.edit', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->edit_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->edit_button;
    }
}
