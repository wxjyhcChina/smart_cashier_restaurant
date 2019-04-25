<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Labels Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in labels throughout the system.
    | Regardless where it is placed, a label can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'general' => [
        'all'     => '全部',
        'yes'     => '是',
        'no'      => '否',
        'custom'  => '自定义',
        'actions' => '操作',
        'active'  => '激活',
        'buttons' => [
            'save'   => '保存',
            'update' => '更新',
        ],
        'hide'              => '隐藏',
        'inactive'          => '禁用',
        'none'              => '空',
        'show'              => '显示',
        'toggle_navigation' => '切换导航',

    ],

    'backend' => [
        'access' => [
            'roles' => [
                'create'     => '新建角色',
                'edit'       => '编辑角色',
                'management' => '角色管理',

                'table' => [
                    'number_of_users' => '用户数',
                    'permissions'     => '权限',
                    'role'            => '角色',
                    'sort'            => '排序',
                    'total'           => '角色总计',
                ],
            ],

            'users' => [
                'active'              => '激活用户',
                'all_permissions'     => '所有权限',
                'change_password'     => '更改密码',
                'change_password_for' => '为 :user 更改密码',
                'create'              => '新建用户',
                'deactivated'         => '已停用的用户',
                'deleted'             => '已删除的用户',
                'edit'                => '编辑用户',
                'management'          => '用户管理',
                'no_permissions'      => '没有权限',
                'no_roles'            => '没有角色可设置',
                'permissions'         => '权限',

                'table' => [
                    'confirmed'      => '确认',
                    'created'        => '创建',
                    'email'          => '电子邮件',
                    'username'       => '用户名',
                    'id'             => 'ID',
                    'last_updated'   => '最后更新',
                    'name'           => '名称',
                    'no_deactivated' => '没有停用的用户',
                    'no_deleted'     => '没有删除的用户',
                    'roles'          => '角色',
                    'social' => 'Social',
                    'total'          => '用户总计',
                    'first_name'     => '名字',
                    'last_name'      => '姓氏',
                ],

                'tabs' => [
                    'titles' => [
                        'overview' => '概述',
                        'history'  => '历史',
                    ],

                    'content' => [
                        'overview' => [
                            'avatar'       => '头像',
                            'confirmed'    => '已确认',
                            'created_at'   => '创建于',
                            'deleted_at'   => '删除于',
                            'email'        => '电子邮件',
                            'last_updated' => '最后更新',
                            'name'         => '名称',
                            'status'       => '状态',
                        ],
                    ],
                ],

                'view' => '查看用户',
            ],
        ],

        'device' => [
            'management' => '设备管理',
            'active' => '所有设备',

            'table' => [
                'id' => '编号',
                'serial_id' => '设备串号',
                'created_at' => '创建时间',
            ]
        ],

        'card' => [
            'management' => 'IC卡管理',
            'active' => '所有IC卡',
            'edit' => '编辑IC卡',

            'table' => [
                'id' => '编号',
                'number' => 'IC卡号',
                'internal_number' => 'IC卡内部编码',
                'status' => '状态',
                'created_at' => '创建时间',
            ]
        ],

        'department' => [
            'management' => '部门管理',
            'active' => '所有部门',
            'edit' => '编辑部门',
            'create' => '创建部门',

            'table' => [
                'id' => '编号',
                'code' => '部门编号',
                'name' => '部门名',
                'created_at' => '创建时间',
            ]
        ],

        'shop' => [
            'management' => '子商户管理',
            'active' => '所有子商户',
            'edit' => '编辑子商户',
            'create' => '创建子商户',

            'table' => [
                'id' => '编号',
                'name' => '子商户名',
                'default' => '默认商户',
                'created_at' => '创建时间',
            ]
        ],

        'dinningTime' => [
            'management' => '用餐时间管理',
            'active' => '所有用餐时间',
            'edit' => '编辑用餐时间',
            'create' => '创建用餐时间',

            'table' => [
                'id' => '编号',
                'name' => '名称',
                'start_time' => '开始时间',
                'end_time' => '结束时间',
            ]
        ],

        'customer' => [
            'management' => '卡用户管理',
            'active' => '所有卡用户',
            'edit' => '编辑卡用户',
            'create' => '创建卡用户',
            'selectCard' => '选择IC卡',
            'change_balance' => '修改余额',
            'change_balance_for' => '为:user修改余额',
            'change_multiple_balance' => '批量补贴用户余额',
            'clear-subsidy-balance' => '批量清空补贴余额',
            'account_record' => '消费记录',
            'consume_order' => '订单记录',
            'bind_card' => '绑定卡',
            'unbind_card' => '解绑卡',
            'lost_card' => '挂失卡',
            'system_add' => '补贴余额',
            'system_minus' => '扣除余额',
            'recharge' => '充值',
            'read_card' => '读卡',

            'table' => [
                'id' => '编号',
                'user_name' => '用户名',
                'id_license' => '身份证号码',
                'birthday' => '生日',
                'telephone' => '联系电话',
                'department' => '部门',
                'consume_category' => '消费类别',
                'balance' => '余额',
                'subsidy_balance' => '补贴余额',
                'total_balance' => '总余额',
                'card' => '绑定卡号',
                'created_at' => '创建时间',
            ],

            'accountRecord' => [
                'record' => '的消费记录',

                'table' => [
                    'id' => '编号',
                    'type' => '订单类型',
                    'money' => '金额',
                    'pay_method_id' => '支付方式',
                    'consume_order' => '消费订单编号',
                    'recharge_order' => '充值订单编号',
                    'created_at' => '时间',
                ],
            ],
        ],

        'goods' => [
            'management' => '商品管理',
            'active' => '所有商品',
            'edit' => '编辑商品',
            'info' => '商品详情',
            'create' => '创建商品',
            'uploadImage' => '上传图片',
            'assignLabelCategory' => '分配餐盘类别',

            'table' => [
                'id' => '编号',
                'name' => '商品名',
                'price' => '价格',
                'shop' => '小店',
                'dinning_time' => '用餐时间',
                'created_at' => '创建时间',
            ]
        ],

        'labelCategory' => [
            'management' => '餐盘类别管理',
            'active' => '所有餐盘类别',
            'edit' => '编辑餐盘类别',
            'create' => '创建餐盘类别',
            'assignLabel' => '分配餐盘',

            'table' => [
                'id' => '编号',
                'name' => '类别名',
                'created_at' => '创建时间',
            ]
        ],

        'label' => [
            'table' => [
                'id' => '编号',
                'rfid' => 'RFID',
                'created_at' => '创建时间'
            ]
        ],

        'consumeCategory' => [
            'management' => '消费类别管理',
            'active' => '所有消费类别',
            'edit' => '编辑消费类别',
            'create' => '创建消费类别',

            'table' => [
                'id' => '编号',
                'name' => '类别名',
                'created_at' => '创建时间',
            ]
        ],

        'consumeRule' => [
            'management' => '消费规则管理',
            'active' => '所有消费规则',
            'edit' => '编辑消费规则',
            'create' => '创建消费规则',

            'table' => [
                'id' => '编号',
                'name' => '规则名',
                'weekday' => '日期',
                'dinningTime' => '用餐时间',
                'consumeCategory' => '消费类别',
                'discount' => '折扣',
                'created_at' => '创建时间',
            ]
        ],

        'payMethod' => [
            'management' => '支付方式管理',
            'active' => '所有支付方式',
            'edit' => '编辑支付方式',
            'alipay' => '编辑支付宝',
            'wechatPay' => '编辑微信支付',

            'table' => [
                'id' => '编号',
                'method' => '支付代码',
                'show_method' => '支付方式',
            ]
        ],

        'consumeOrder' => [
            'management' => '消费记录管理',
            'active' => '所有消费记录',
            'info' => '消费记录详情',
            'orderFor' => '的消费记录',
            'searchTime' => '搜索时间',
            'search' => '搜索',

            'dinning_time' => '选择时间段',
            'pay_method' => '支付方式',
            'restaurant_user' => '营业员',


            'table' => [
                'id' => '编号',
                'order_id' => '订单编号',
                'customer_id' => '用户编号',
                'card_id' => '卡编号',
                'price' => '价格',
                'pay_method' => '支付方式',
                'dinning_time' => '用餐时间',
                'created_at' => '消费时间',
                'restaurant_user_id' => '营业员',
                'status' => '状态'
            ],

            'status' => [
                'refunded' => '已退款',
                'refund_in_progress' => '退款中',
                'wait_pay' => '等待支付',
                'pay_in_progress' => '支付中',
                'complete' => '已完成',
                'closed' => '已关闭',
            ]
        ],

        'rechargeOrder' => [
            'management' => '充值记录管理',
            'active' => '所有充值记录',
            'orderFor' => '的充值记录',

            'table' => [
                'id' => '编号',
                'order_id' => '订单编号',
                'customer_id' => '用户编号',
                'card_id' => '卡编号',
                'price' => '价格',
                'pay_method' => '支付方式',
                'restaurant_user_id' => '营业员',
                'status' => '状态',
                'created_at' => '充值时间',
            ],

            'status' => [
                'refunded' => '已退款',
                'refund_in_progress' => '退款中',
                'wait_pay' => '等待支付',
                'pay_in_progress' => '支付中',
                'complete' => '已完成',
                'closed' => '已关闭',
            ]
        ]
    ],

    'frontend' => [

        'auth' => [
            'login_box_title'    => '登录',
            'login_button'       => '登录',
            'login_with'         => '使用 :social_media 登录',
            'register_box_title' => '注册',
            'register_button'    => '注册',
            'remember_me'        => '记住我',
        ],

        'contact' => [
            'box_title' => 'Contact Us',
            'button' => 'Send Information',
        ],

        'passwords' => [
            'forgot_password'                 => '忘记密码了？',
            'reset_password_box_title'        => '重置密码',
            'reset_password_button'           => '重置密码',
            'send_password_reset_link_button' => '发送密码重置链接',
        ],

        'macros' => [
            'country' => [
                'alpha'   => 'Country Alpha Codes',
                'alpha2'  => 'Country Alpha 2 Codes',
                'alpha3'  => 'Country Alpha 3 Codes',
                'numeric' => 'Country Numeric Codes',
            ],

            'macro_examples' => 'Macro Examples',

            'state' => [
                'mexico' => 'Mexico State List',
                'us'     => [
                    'us'       => 'US States',
                    'outlying' => 'US Outlying Territories',
                    'armed'    => 'US Armed Forces',
                ],
            ],

            'territories' => [
                'canada' => 'Canada Province & Territories List',
            ],

            'timezone' => 'Timezone',
        ],

        'user' => [
            'passwords' => [
                'change' => '更改密码',
            ],

            'profile' => [
                'avatar'             => '头像',
                'created_at'         => '创建于',
                'edit_information'   => '编辑信息',
                'email'              => '电子邮件',
                'last_updated'       => '最后更新',
                'name'               => '名称',
                'update_information' => '更新信息',
            ],
        ],

    ],
];
