<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'title' => '权限管理',

            'roles' => [
                'all'        => '所有角色',
                'create'     => '新建角色',
                'edit'       => '编辑角色',
                'management' => '角色管理',
                'main'       => '角色',
            ],

            'users' => [
                'all'             => '所有用户',
                'change-password' => '更改密码',
                'create'          => '新建用户',
                'deactivated'     => '未激活的用户',
                'deleted'         => '已删除的用户',
                'edit'            => '编辑用户',
                'main'            => '用户',
                'view'            => '查看用户',
            ],
        ],

        'card' => [
            'title' => 'IC卡管理',
            'all' => '所有IC卡',
            'create' => '创建IC卡',
            'import' => '导入IC卡',
        ],

        'device' => [
            'title' => '设备管理',
        ],

        'department' => [
            'title' => '部门管理',
            'all' => '所有部门',
            'create' => '创建部门',
        ],

        'shop' => [
            'title' => '子商户管理',
            'all' => '所有子商户',
            'create' => '创建子商户',
        ],

        'dinningTime' => [
            'title' => '用餐时间管理',
            'all' => '所有用餐时间',
            'create' => '创建用餐时间',
        ],

        'consumeCategory' => [
            'title' => '消费类别管理',
            'all' => '所有消费类别',
            'create' => '创建消费类别',
        ],

        'consumeRule' => [
            'title' => '消费规则管理',
            'all' => '所有消费规则',
            'create' => '创建消费规则',
        ],

        'customer' => [
            'title' => '卡用户管理',
            'all' => '所有卡用户',
            'create' => '创建卡用户',
            'changeBalance' => '修改余额',
            'changeMultipleBalance' => '补贴多用户金额',
            'clearSubsidyBalance' => '清空补贴金额',
        ],

        'goodCategory' => [
            'title' => '商品大类管理',
            'all' => '所有商品大类',
            'create' => '创建商品大类',
        ],

        'goods' => [
            'title' => '商品管理',
            'all' => '所有商品',
            'create' => '创建商品',
        ],

        'labelCategory' => [
            'title' => '餐盘类别管理',
            'all' => '所有餐盘类别',
            'create' => '创建餐盘类别',
        ],

        'payMethod' => [
            'title' => '支付管理',
        ],

        'consumeOrder' => [
            'title' => '消费记录'
        ],

        'rechargeOrder' => [
            'title' => '充值记录'
        ],

        'statistics' => [
            'title' => '统计查询',
        ],

        'stock' => [
            'title' => '进销存管理',
            'purchase'=>'采购管理',
            'consume'=>'每日消耗',
            'stock'=>'库存管理',
            'stock-all'=>'所有材料',
            'create'=>'新增材料',
            'frmLoss'=>'报损'
        ],

        'log-viewer' => [
            'main'      => '日志查看器',
            'dashboard' => '指示板',
            'logs'      => '日志',
        ],

        'sidebar' => [
            'dashboard' => '指示板',
            'general'   => '常规',
            'system'    => '系统',
        ],
    ],

    'language-picker' => [
        'language' => '语言',
        /*
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'ar'    => '阿拉伯语（Arabic）',
            'zh'    => '中文（Chinese Simplified）',
            'zh-TW' => '中文（Chinese Traditional）',
            'da'    => '丹麦语（Danish）',
            'de'    => '德语（German）',
            'el'    => '希腊语（Greek）',
            'en'    => '英语（English）',
            'es'    => '西班牙语（Spanish）',
            'fr'    => '法语（French）',
            'id'    => '印度尼西亚语（Indonesian）',
            'it'    => '意大利语（Italian）',
            'ja'    => '日语（Japanese）',
            'nl'    => '荷兰语（Dutch）',
            'pt_BR' => '巴西葡萄牙语（Brazilian Portuguese）',
            'ru'    => '俄语（Russian）',
            'sv'    => '瑞典语（Swedish）',
            'th'    => '泰语（Thai）',
            'tr'    => '(Turkish)',
        ],
    ],
];
