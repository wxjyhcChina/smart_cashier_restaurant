<?php

namespace App\Modules\Models\Restaurant\Traits\Attribute;

/**
 * Class RestaurantUserAttribute
 * @package App\Modules\Models\Restaurant\Traits\Attribute
 */
trait RestaurantUserAttribute
{
    /**
     * @return string
     */
    public function getChangePasswordButtonAttribute()
    {
        return '<a href="'.route('admin.restaurant.change-password', [$this->restaurant, $this], false).'" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.change_password').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->change_password_button;
    }
}
