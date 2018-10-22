<?php

namespace App\Modules\Models\PayMethod\Traits\Relationship;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\AlipayDetail;
use App\Modules\Models\PayMethod\WechatPayDetail;

/**
 * Class PayMethodRelationship
 * @package App\Modules\Models\PayMethod\Traits\Relationship
 */
trait PayMethodRelationship
{
    /**
     * @return mixed
     */
    public function alipay_detail()
    {
        return $this->hasOne(AlipayDetail::class);
    }

    /**
     * @return mixed
     */
    public function wechat_pay_detail()
    {
        return $this->hasOne(WechatPayDetail::class);
    }
}