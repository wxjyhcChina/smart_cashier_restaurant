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
        'resource_not_exist' => '资源不存在',

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

        'card_not_exist' => '卡不存在',
        'card_status_incorrect' => '卡状态不正确，请换卡后操作',

        'not_in_dinning_time' => '当前时间不在用餐时间',

        'consume_rule_conflict' => '消费规则时间冲突，请检查后再试',

        'label_not_exist' => '餐盘不存在',
        'label_category_not_binded' => '餐盘未绑定类别',
        'label_category_already_binded' => '餐盘已经绑定其他商品',
        'label_category_not_bind_good' => '餐盘类别未绑定商品',

        'goods_not_exist' => '商品不存在',

        'order_goods_not_exist' => '订单中不存在商品',
        'order_status_incorrect' => '订单状态不正确'
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