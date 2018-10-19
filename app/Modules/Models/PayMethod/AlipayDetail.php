<?php

namespace App\Modules\Models\PayMethod;

use Illuminate\Database\Eloquent\Model;

class AlipayDetail extends Model
{
    protected $table = 'alipay_detail';

    protected $fillable = ['id', 'pay_method_id', 'app_id', 'pid', 'pub_key_path', 'mch_private_key_path'];
}