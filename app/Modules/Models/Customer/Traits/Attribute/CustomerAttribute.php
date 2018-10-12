<?php

namespace App\Modules\Models\Customer\Traits\Attribute;

/**
 * Trait CustomerAttribute
 * @package App\Modules\Models\Customer\Traits\Attribute
 */
trait CustomerAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.customer.edit', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }


    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
        switch ($this->enabled) {
            case 0:
                return '<a href="' . route('admin.customer.mark', [
                        $this,
                        1
                    ]) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.access.users.activate') . '"></i></a> ';
            // No break

            case 1:
                return '<a href="' . route('admin.customer.mark', [
                        $this,
                        0
                    ]) . '" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.access.users.deactivate') . '"></i></a> ';
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
            $this->edit_button.
            $this->status_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->edit_button.
            $this->status_button;
    }
}