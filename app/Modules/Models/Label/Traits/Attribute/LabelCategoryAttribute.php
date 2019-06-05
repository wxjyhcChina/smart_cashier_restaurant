<?php

namespace App\Modules\Models\Label\Traits\Attribute;

/**
 * Trait LabelCategoryAttribute
 * @package App\Modules\Models\Label\Traits\Attribute
 */
trait LabelCategoryAttribute
{
    /**
     * @return string
     */
    public function getInfoButtonAttribute()
    {
        return '<a href="' . route('admin.labelCategory.info', $this, false) . '" class="btn btn-xs btn-info"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.info') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.labelCategory.edit', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getGoodsButtonAttribute()
    {
        return '<a href="' . route('admin.labelCategory.goods', $this, false) . '" class="btn btn-xs btn-info"><i class="fa fa-cutlery" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.labelCategory.goods') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getAssignLabelButtonAttribute()
    {
        return '<a href="' . route('admin.labelCategory.assignLabel', $this, false) . '" class="btn btn-xs btn-success"><i class="fa fa-tag" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.labelCategory.assignLabel') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->info_button .
            $this->edit_button .
            $this->goods_button .
            $this->assign_label_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->info_button .
            $this->edit_button .
            $this->goods_button .
            $this->assign_label_button;
    }
}
