<?php

namespace App\Modules\Models\ConsumeOrder;

use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\ConsumeOrder\Traits\Attribute\ConsumeOrderAttribute;
use App\Modules\Models\ConsumeOrder\Traits\Relationship\ConsumeOrderRelationship;
use Illuminate\Database\Eloquent\Model;

class ConsumeOrder extends Model
{
    use ConsumeOrderAttribute, ConsumeOrderRelationship;

    protected $fillable = ['customer_id', 'card_id', 'restaurant_id', 'restaurant_user_id', 'price', 'discount', 'pay_method', 'online_pay', 'external_pay_no', 'status'];

    public function getShowPayMethodAttribute()
    {
        switch ($this->pay_method)
        {
            case PayMethodType::CARD:
                $ret = trans('api.wallet.pay_method.card');
                break;
            case PayMethodType::CASH:
                $ret = trans('api.wallet.pay_method.cash');
                break;
            case PayMethodType::ALIPAY:
                $ret = trans('api.wallet.pay_method.alipay');
                break;
            case PayMethodType::WECHAT_PAY:
                $ret = trans('api.wallet.pay_method.wechat');
                break;

            default:
                $ret = '';
        }

        return $ret;
    }

    public function getShowStatusAttribute()
    {
        $status = $this->status;

        $status_str = '';
        switch ($status)
        {
            case ConsumeOrderStatus::REFUNDED:
                $status_str = '<span class="label label-danger">'.trans('labels.backend.consumeOrder.status.refunded').'</span>';
                break;
            case ConsumeOrderStatus::REFUND_IN_PROGRESS:
                $status_str = '<span class="label label-danger">'.trans('labels.backend.consumeOrder.status.refund_in_progress').'</span>';
                break;
            case ConsumeOrderStatus::WAIT_PAY:
                $status_str = '<span class="label label-warning">'.trans('labels.backend.consumeOrder.status.wait_pay').'</span>';
                break;
            case ConsumeOrderStatus::PAY_IN_PROGRESS:
                $status_str = '<span class="label label-primary">'.trans('labels.backend.consumeOrder.status.pay_in_progress').'</span>';
                break;
            case ConsumeOrderStatus::COMPLETE:
                $status_str = '<span class="label label-success">'.trans('labels.backend.consumeOrder.status.complete').'</span>';
                break;
            case ConsumeOrderStatus::CLOSED:
                $status_str = '<span class="label label-danger">'.trans('labels.backend.consumeOrder.status.closed').'</span>';
                break;
        }

        return $status_str;
    }
}