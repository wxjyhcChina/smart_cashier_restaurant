<?php

namespace App\Modules\Models\RechargeOrder;

use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\RechargeOrder\Traits\Attribute\RechargeOrderAttribute;
use App\Modules\Models\RechargeOrder\Traits\Relationship\RechargeOrderRelationship;
use Illuminate\Database\Eloquent\Model;

class RechargeOrder extends Model
{
    use RechargeOrderAttribute, RechargeOrderRelationship;

    protected $fillable = ['customer_id', 'card_id', 'restaurant_id', 'restaurant_user_id', 'money', 'discount', 'pay_method', 'external_pay_no', 'status'];

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
                $status_str = '<span class="label label-danger">'.trans('labels.backend.rechargeOrder.status.refunded').'</span>';
                break;
            case ConsumeOrderStatus::REFUND_IN_PROGRESS:
                $status_str = '<span class="label label-danger">'.trans('labels.backend.rechargeOrder.status.refund_in_progress').'</span>';
                break;
            case ConsumeOrderStatus::WAIT_PAY:
                $status_str = '<span class="label label-warning">'.trans('labels.backend.rechargeOrder.status.wait_pay').'</span>';
                break;
            case ConsumeOrderStatus::PAY_IN_PROGRESS:
                $status_str = '<span class="label label-primary">'.trans('labels.backend.rechargeOrder.status.pay_in_progress').'</span>';
                break;
            case ConsumeOrderStatus::COMPLETE:
                $status_str = '<span class="label label-success">'.trans('labels.backend.rechargeOrder.status.complete').'</span>';
                break;
            case ConsumeOrderStatus::CLOSED:
                $status_str = '<span class="label label-danger">'.trans('labels.backend.rechargeOrder.status.closed').'</span>';
                break;
        }

        return $status_str;
    }
}