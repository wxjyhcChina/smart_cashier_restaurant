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

            'table' => [
                'id' => '编号',
                'number' => 'IC卡号',
                'internal_number' => 'IC卡内部编码',
                'status' => '状态',
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
