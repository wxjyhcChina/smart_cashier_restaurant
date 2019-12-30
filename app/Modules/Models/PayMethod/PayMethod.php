<?php

namespace App\Modules\Models\PayMethod;

use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\Traits\Attribute\PayMethodAttribute;
use App\Modules\Models\PayMethod\Traits\Relationship\PayMethodRelationship;
use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    use PayMethodAttribute, PayMethodRelationship;

    protected $fillable = ['id', 'restaurant_id', 'method', 'enabled'];

    public function getShowMethodName()
    {
        $method = $this->method;
        $str = '';

        switch ($method)
        {
            case PayMethodType::CASH:
                $str = '现金支付';
                break;
            case PayMethodType::CARD:
                $str = '卡支付';
                break;
            case PayMethodType::ALIPAY:
                $str = '支付宝支付';
                break;
            case PayMethodType::WECHAT_PAY:
                $str = '微信支付';
                break;
        }

        return $str;
    }
}