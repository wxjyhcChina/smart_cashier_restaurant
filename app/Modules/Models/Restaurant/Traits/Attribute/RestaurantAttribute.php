<?php

namespace App\Modules\Models\Restaurant\Traits\Attribute;

/**
 * Class RestaurantAttribute
 * @package App\Modules\Models\BusInfo\Traits\Attribute
 */
trait RestaurantAttribute
{
    /**
     * @return string
     */
    public function getInfoButtonAttribute()
    {
        return '<a href="' . route('admin.restaurant.info', $this, false) . '" class="btn btn-xs btn-info"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.info') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.restaurant.edit', $this, false) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getAssignCardButtonAttribute()
    {
        return '<a href="' . route('admin.restaurant.assignCard', $this, false) . '" class="btn btn-xs btn-success"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.agent.assignCard') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEnabledButtonAttribute()
    {
        switch ($this->enabled) {
            case 0:
                return '<a href="' . route('admin.restaurant.mark', [
                        $this,
                        1
                    ], false) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.access.users.activate') . '"></i></a> ';
            // No break

            case 1:
                return '<a href="' . route('admin.restaurant.mark', [
                        $this,
                        0
                    ], false) . '" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.access.users.deactivate') . '"></i></a> ';
            // No break

            default:
                return '';
            // No break
        }
    }
    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->info_button .
            $this->edit_button .
            $this->assign_card_button .
            $this->enabled_button ;
    }
}
