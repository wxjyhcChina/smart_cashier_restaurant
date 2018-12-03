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


    //qiniu configuration
    'qiniu' => [
        'access_key' => env('QINIU_ACCESS_KEY'),
        'secret' => env('QINIU_SECRET'),
        'image_bucket' => env('QINIU_IMAGE_BUCKET'),
        'image_bucket_url' => env('QINIU_IMAGE_BUCKET_URL'),
        'download_bucket' => env('QINIU_DOWNLOAD_BUCKET'),
        'download_bucket_url' => env('QINIU_DOWNLOAD_BUCKET_URL'),
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
        'unified_order_url' => env('WECHAT_UNIFIED_ORDER_URL'),
        'open_id_url' => env('WECHAT_OPEN_ID_URL'),
        'refund_url' => env('WECHAT_REFUND_URL'),
        'micropay_url' => env('WECHAT_MICROPAY_URL'),
        'order_query_url' => env('WECHAT_ORDER_QUERY_URL'),
        'order_reverse_url' => env('WECHAT_ORDER_REVERSE_URL'),
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