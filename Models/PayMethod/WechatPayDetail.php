<?php

namespace App\Modules\Models\PayMethod;

use Illuminate\Database\Eloquent\Model;

class WechatPayDetail extends Model
{
    protected $table = 'wechat_pay_detail';

    protected $fillable = ['id', 'pay_method_id', 'app_id', 'app_secret', 'mch_id', 'mch_api_key', 'ssl_cert_path', 'ssl_key_path'];
}