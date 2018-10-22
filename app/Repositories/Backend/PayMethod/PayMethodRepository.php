<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\PayMethod;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\AlipayDetail;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\PayMethod\WechatPayDetail;
use App\Modules\Repositories\PayMethod\BasePayMethodRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Class PayMethodRepository
 * @package App\Repositories\Backend\PayMethod
 */
class PayMethodRepository extends BasePayMethodRepository
{
    public function update(PayMethod $payMethod, $input)
    {
        if ($payMethod->method == PayMethodType::ALIPAY)
        {
            $alipayDetail = $payMethod->alipay_detail;

            if ($alipayDetail == null)
            {
                $alipayDetail = new AlipayDetail();
                $alipayDetail->pay_method_id = $payMethod->id;
            }

            $alipayDetail->app_id = $input['app_id'];
            $alipayDetail->pid = $input['pid'];

            $pub_key_path = "alipay/public_key.pem";
            $mch_private_key_path = "alipay/mch_private_key.pem";

            Storage::disk('cert')->put("$payMethod->restaurant_id/$pub_key_path", $input['pub_key']);
            Storage::disk('cert')->put("$payMethod->restaurant_id/$mch_private_key_path", $input['mch_private_key']);

            $alipayDetail->pub_key_path = $pub_key_path;
            $alipayDetail->mch_private_key_path = $mch_private_key_path;

            $alipayDetail->save();
        }
        else if ($payMethod->method == PayMethodType::WECHAT_PAY)
        {
            $wechatPayDetail = $payMethod->wechat_pay_detail;

            if ($wechatPayDetail == null)
            {
                $wechatPayDetail = new WechatPayDetail();
                $wechatPayDetail->pay_method_id = $payMethod->id;
            }

            $wechatPayDetail->app_id = $input['app_id'];
            $wechatPayDetail->mch_id = $input['mch_id'];
            $wechatPayDetail->mch_api_key = $input['mch_api_key'];

            $cert_path = "wechat/apiclient_cert.pem";
            $key_path = "wechat/apiclient_key.pem";

            Storage::disk('cert')->put("$payMethod->restaurant_id/$cert_path", $input['ssl_cert']);
            Storage::disk('cert')->put("$payMethod->restaurant_id/$key_path", $input['ssl_key']);

            $wechatPayDetail->ssl_cert_path = $cert_path;
            $wechatPayDetail->ssl_key_path = $key_path;

            $wechatPayDetail->save();
        }
    }

    /**
     * @param PayMethod $payMethod
     * @param $enabled
     * @return bool
     * @throws ApiException
     */
    public function mark(PayMethod $payMethod, $enabled)
    {
        if ($enabled == 1 &&
            (($payMethod->method == PayMethodType::ALIPAY && $payMethod->alipay_detail == null)
            || ($payMethod->method == PayMethodType::WECHAT_PAY && $payMethod->wechat_pay_detail == null))
        )
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('exceptions.backend.payMethod.mark_pay_detail_error'));
        }

        $payMethod->enabled = $enabled;

        if ($payMethod->save()) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.payMethod.mark_error'));
    }
}