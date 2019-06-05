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
    public function getConsumeOrderButtonAttribute()
    {
        return '<a href="' . route('admin.customer.consumeOrders', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-bars" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.consume_order') . '"></i></a> ';
    }

    public function getBindCardButtonAttribute()
    {
        if ($this->card == null)
        {
            return '<a href="' . route('admin.customer.bindCard', $this) . '" class="btn btn-xs btn-success"><i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.bind_card') . '"></i></a> ';
        }

        return null;
    }

    public function getUnbindCardButtonAttribute()
    {
        if ($this->card != null)
        {
            return '<a href="' . route('admin.customer.unbindCard', $this) . '" class="btn btn-xs btn-danger"><i class="fa fa-unlock" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.unbind_card') . '"></i></a> ';
        }

        return null;
    }

    public function getLostCardButtonAttribute()
    {
        if ($this->card != null)
        {
            return '<a href="' . route('admin.customer.lostCard', $this) . '" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.lost_card') . '"></i></a> ';
        }

        return null;
    }

    /**
     * @return string
     */
    public function getAccountRecordButtonAttribute()
    {
        return '<a href="' . route('admin.customer.accountRecords', $this) . '" class="btn btn-xs btn-success"><i class="fa fa-exchange" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.account_record') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getRechargeButtonAttribute()
    {
        if ($this->card != null)
        {
            return '<a href="' . route('admin.customer.recharge', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-cny" data-toggle="tooltip" data-placement="top" title="' . trans('labels.backend.customer.recharge') . '"></i></a> ';
        }

        return null;
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
            $this->consume_order_button .
            $this->account_record_button .
            $this->recharge_button;
    }

    /**
     * @return string
     */
    public function getRestaurantActionButtonsAttribute()
    {
        return
            $this->edit_button.
            $this->consume_order_button .
            $this->account_record_button .
            $this->bind_card_button .
            $this->unbind_card_button .
            $this->lost_card_button .
            $this->recharge_button;
    }
}
