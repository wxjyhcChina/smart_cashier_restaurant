<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 3:38 PM
 */

return [
    'common' => [
        'temp_file_location' => env('TEMP_FILE_LOCATION'),
    ],

    //sms configuration
    'sms' => [
        //sms related config
        'base_url' => env('SMS_BASE_URL'),
        'template_url' => env('SMS_TEMPLATE_URL'),
        'code_url' => env('SMS_CODE_URL'),
        'verify_url' => env('SMS_VERIFY_URL'),
        'app_key' => env('SMS_APP_KEY'),
        'app_Secret' => env('SMS_APP_SECRET'),
        'code_len' => env('SMS_CODE_LEN'),
        'template_id' => env('SMS_TEMPLATE_ID'),
    ],

    //qiniu configuration
    'qiniu' => [
        'access_key' => env('QINIU_ACCESS_KEY'),
        'secret' => env('QINIU_SECRET'),
        'callback_base_url' => env('QINIU_CALLBACK_BASE_URL'),
        'user_bucket' => env('QINIU_USER_BUCKET'),
        'user_bucket_url' => env('QINIU_USER_BUCKET_URL'),
        'shop_bucket' => env('QINIU_SHOP_BUCKET'),
        'shop_bucket_url' => env('QINIU_SHOP_BUCKET_URL'),
        'repair_bucket' => env('QINIU_REPAIR_BUCKET'),
        'repair_bucket_url' => env('QINIU_REPAIR_BUCKET_URL'),
    ],

    'jpush' => [
        'access_key' => env('JPUSH_ACCESS_KEY'),
        'secret' => env('JPUSH_SECRET'),
    ],

    'amap' => [
        'url' => env('AMAP_API_URL'),
        'key' => env('AMAP_KEY'),
    ],

    'wechat' => [
        'app_id' => env('WECHAT_APPID'),
        'mini_program_id' => env('WECHAT_MINI_PROGRAM_ID'),
        'js_id' => env('WECHAT_JS_ID'),
        'app_secret' => env('WECHAT_APPSECRET'),
        'mini_program_secret' => env('WECHAT_MINI_PROGRAM_SECRET'),
        'mch_id' => env('WECHAT_MCHID'),
        'api_key' => env('WECHAT_KEY'),
        'unified_order_url' => env('WECHAT_UNIFIED_ORDER_URL'),
        'open_id_url' => env('WECHAT_OPEN_ID_URL'),
        'refund_url' => env('WECHAT_REFUND_URL'),
        'ssl_cert_path' => __DIR__."/../".env('WECHAT_SSL_CERT_PATH'),
        'ssl_key_path' => __DIR__."/../".env('WECHAT_SSL_KEY_PATH'),
    ],

    'alipay' => [
        'app_id' => env('ALIPAY_APPID'),
        'alipay_pub_key' => __DIR__."/../".env('ALIPAY_PUB_KEY'),
        'alipay_mch_pub_key' => __DIR__."/../".env('ALIPAY_MCH_PUB_KEY'),
        'alipay_mch_private_key' => __DIR__."/../".env('ALIPAY_MCH_PRIVATE_KEY'),
    ],

    'heCloud' => [
        'api_url' => env('HE_CLOUD_API_ADDRESS'),
        'api_key' => env('HE_CLOUD_API_KEY'),
        'token' => env('HE_CLOUD_TOKEN'),
    ],

    'order' => [
        'exclude_time' => env('ORDER_EXCLUDE_TIME')
    ]
];