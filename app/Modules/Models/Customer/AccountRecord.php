<?php

namespace App\Modules\Models\Customer;

use App\Modules\Enums\AccountRecordType;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\Customer\Traits\Relationship\AccountRecordRelationship;
use Illuminate\Database\Eloquent\Model;

class AccountRecord extends Model
{
    use AccountRecordRelationship;

    protected $fillable = ['id', 'customer_id','account_id', 'card_id', 'type', 'recharge_order_id', 'consume_order_id', 'money', 'pay_method'];

    public function getMoneyAttribute($value)
    {
        if ($this->type == AccountRecordType::CONSUME || $this->type == AccountRecordType::SYSTEM_MINUS)
        {
            $value = -$value;
        }

        return $value.'元';
    }

    public function getTypeNameAttribute()
    {
        $ret = "";
        switch ($this->type)
        {
            case AccountRecordType::RECHARGE:
                $ret = trans('api.wallet.type.recharge');
                break;
            case AccountRecordType::REFUND:
                $ret = trans('api.wallet.type.refund');
                break;
            case AccountRecordType::SYSTEM_ADD:
                $ret = trans('api.wallet.type.system_add');
                break;
            case AccountRecordType::CONSUME:
                $ret = trans('api.wallet.type.consume');
                break;
            case AccountRecordType::SYSTEM_MINUS:
                $ret = trans('api.wallet.type.system_minus');
                break;
        }

        return $ret;
    }

    public function getShowNameAttribute()
    {
        $ret = $this->getTypeNameAttribute();

        if ($this->type == AccountRecordType::RECHARGE ||
            $this->type == AccountRecordType::SYSTEM_ADD ||
            $this->type == AccountRecordType::REFUND)
        {
            $ret = '<span class="label label-success">'.$ret.'</span>';
        }
        else
        {
            $ret = '<span class="label label-danger">'.$ret.'</span>';
        }

        return $ret;
    }

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
}