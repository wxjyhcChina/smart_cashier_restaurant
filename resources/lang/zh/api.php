<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 5:27 PM
 */

return [
    'error' => [
        'database_error' => '数据库更新错误',
        'param_error' => '参数错误',

        'token_not_provide' => 'token未提供',
        'token_expire' => 'token已经过期',
        'token_invalid' => 'token无效',
        'sms_error' => '验证码发送失败，请稍后重试',
        'sms_invalid_type' => '发送的验证码类型有误',
        'user_not_exist' => '该用户不存在',
        'user_already_exist' => '该用户已经存在',
        'login_failed' => '用户名或认证码错误',
        'create_token_failed' => '创建token失败',
        'input_incomplete' => '输入信息不完整',
        'user_have_not_money' => '用户余额不足，无法借用充电宝',
        'user_create_failed' => '用户创建失败',
        'open_id_access_token_failed' => 'openId或accessToken错误',

        'qr_code_invalid' => '二维码不正确',

        'he_cloud_api_error' => '接口错误',
        'device_not_online' => '当前设备不在线，请稍后重试!',
        'device_not_exist' => '设备不存在',
        'not_have_battery' => '没有可用充电宝',
        'already_have_order' => '当前已经有在使用的充电宝，请归还后再试!',
        'not_have_order' => '当前没有充电宝订单',
        'device_create_failed' => '设备创建失败',

        'box_not_exist' => '充电箱不存在',
        'box_already_binded' => '充电箱已经被绑定',
        'box_locked' => '充电箱正忙，请稍后',

        'sn_not_exist' => '二维码不存在',
    ],

    'pay' => [
        'deposit' => '押金支付',
        'recharge' => '充值',
    ],

    'wallet' => [
        'type' => [
            'deposit' => '押金支付',
            'deposit_refund' => '押金退款',
            'recharge' => '充值',
            'consume' => '消费',
        ],

        'pay_method' => [
            'alipay' => '支付宝支付',
            'wechat' => '微信支付',
        ]
    ]
];