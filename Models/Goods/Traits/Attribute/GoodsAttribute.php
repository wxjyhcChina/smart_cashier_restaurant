<?php

namespace App\Modules\Models\Goods\Traits\Attribute;

/**
 * Trait GoodsAttribute
 * @package App\Modules\Models\Goods\Traits\Attribute
 */
trait GoodsAttribute
{
    /**
     * @return string
     */
    public function getInfoButtonAttribute()
    {
        return '<a href="' . route('admin.goods.info', $this, false) . '" class="btn btn-xs btn-info"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.info') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.goods.edit', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getAssignLabelCategoryButtonAttribute()
    {
        return '<a href="' . route('admin.goods.assignLabelCategory', $this, false) . '" class="btn btn-xs btn-success"><i class="fa fa-tag" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.goods.assignLabelCategory') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.goods.destroy', $this, false).'"
                 data-method="delete"
                 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->info_button .
            $this->edit_button .
            $this->assign_label_category_button .
            $this->delete_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->info_button .
            $this->edit_button .
            $this->assign_label_category_button .
            $this->delete_button;
    }
}
