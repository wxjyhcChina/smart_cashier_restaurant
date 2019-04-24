<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必须接受。',
    'active_url'           => ':attribute 不是一个有效的网址。',
    'after'                => ':attribute 必须要晚于 :date。',
    'after_or_equal'       => ':attribute 必须要等于 :date 或更晚。',
    'alpha'                => ':attribute 只能由字母组成。',
    'alpha_dash'           => ':attribute 只能由字母、数字和斜杠组成。',
    'alpha_num'            => ':attribute 只能由字母和数字组成。',
    'array'                => ':attribute 必须是一个数组。',
    'before'               => ':attribute 必须要早于 :date。',
    'before_or_equal'      => ':attribute 必须要等于 :date 或更早。',
    'between'              => [
        'numeric' => ':attribute 必须介于 :min - :max 之间。',
        'file'    => ':attribute 必须介于 :min - :max kb 之间。',
        'string'  => ':attribute 必须介于 :min - :max 个字符之间。',
        'array'   => ':attribute 必须只有 :min - :max 个单元。',
    ],
    'boolean'              => ':attribute 必须为布尔值。',
    'confirmed'            => ':attribute 两次输入不一致。',
    'date'                 => ':attribute 不是一个有效的日期。',
    'date_format'          => ':attribute 的格式必须为 :format。',
    'different'            => ':attribute 和 :other 必须不同。',
    'digits'               => ':attribute 必须是 :digits 位的数字。',
    'digits_between'       => ':attribute 必须是介于 :min 和 :max 位的数字。',
    'dimensions'           => ':attribute 图片尺寸不正确。',
    'distinct'             => ':attribute 已经存在。',
    'email'                => ':attribute 不是一个合法的邮箱。',
    'exists'               => ':attribute 不存在。',
    'file'                 => ':attribute 必须是文件。',
    'filled'               => ':attribute 不能为空。',
    'image'                => ':attribute 必须是图片。',
    'in'                   => '已选的属性 :attribute 非法。',
    'in_array'             => ':attribute 没有在 :other 中。',
    'integer'              => ':attribute 必须是整数。',
    'ip'                   => ':attribute 必须是有效的 IP 地址。',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => ':attribute 必须是正确的 JSON 格式。',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max。',
        'file'    => ':attribute 不能大于 :max kb。',
        'string'  => ':attribute 不能大于 :max 个字符。',
        'array'   => ':attribute 最多只有 :max 个单元。',
    ],
    'mimes'                => ':attribute 必须是一个 :values 类型的文件。',
    'mimetypes'            => ':attribute 必须是一个 :values 类型的文件。',
    'min'                  => [
        'numeric' => ':attribute 必须大于等于 :min。',
        'file'    => ':attribute 大小不能小于 :min kb。',
        'string'  => ':attribute 至少为 :min 个字符。',
        'array'   => ':attribute 至少有 :min 个单元。',
    ],
    'not_in'               => '已选的属性 :attribute 非法。',
    'numeric'              => ':attribute 必须是一个数字。',
    'present'              => ':attribute 必须存在。',
    'regex'                => ':attribute 格式不正确。',
    'required'             => ':attribute 不能为空。',
    'required_if'          => '当 :other 为 :value 时 :attribute 不能为空。',
    'required_unless'      => '当 :other 不为 :value 时 :attribute 不能为空。',
    'required_with'        => '当 :values 存在时 :attribute 不能为空。',
    'required_with_all'    => '当 :values 存在时 :attribute 不能为空。',
    'required_without'     => '当 :values 不存在时 :attribute 不能为空。',
    'required_without_all' => '当 :values 都不存在时 :attribute 不能为空。',
    'same'                 => ':attribute 和 :other 必须相同。',
    'size'                 => [
        'numeric' => ':attribute 大小必须为 :size。',
        'file'    => ':attribute 大小必须为 :size kb。',
        'string'  => ':attribute 必须是 :size 个字符。',
        'array'   => ':attribute 必须为 :size 个单元。',
    ],
    'string'               => ':attribute 必须是一个字符串。',
    'timezone'             => ':attribute 必须是一个合法的时区值。',
    'unique'               => ':attribute 已经存在。',
    'uploaded'             => ':attribute 上传失败。',
    'url'                  => ':attribute 格式不正确。',
    'sms_verify'           => '验证码不正确!',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [

        'backend' => [
            'access' => [
                'permissions' => [
                    'associated_roles' => '关联的角色',
                    'dependencies'     => '依赖关系',
                    'display_name'     => '显示名称',
                    'group'            => '群组',
                    'group_sort'       => '组排序',

                    'groups' => [
                        'name' => '组名称',
                    ],

                    'name'   => '名称',
                    'system' => '系统?',
                ],

                'roles' => [
                    'associated_permissions' => '关联的权限',
                    'name'                   => '名称',
                    'sort'                   => '排序',
                ],

                'users' => [
                    'active'                  => '激活',
                    'associated_roles'        => '关联的角色',
                    'confirmed'               => '已确认',
                    'email'                   => '电子邮件地址',
                    'name'                    => '名称',
                    'other_permissions'       => '其他权限',
                    'password'                => '密码',
                    'password_confirmation'   => '确认密码',
                    'send_confirmation_email' => '发送确认电子邮件',
                    'username'                 => '用户名',
                    'first_name'              => '名字',
                    'last_name'               => '姓氏',
                ],
            ],

            'card' => [
                'number' => 'IC卡号',
                'status' => '状态',
            ],

            'device' => [
                'serial_id' => '设备串号',
            ],

            'department' => [
                'code' => '部门编码',
                'name' => '部门名'
            ],

            'shop' => [
                'name' => '子商户名',
                'default' => '默认'
            ],

            'dinningTime' => [
                'name' => '名称',
                'time' => '用餐时间',
                'start_time' => '开始时间',
                'end_time' => '结束时间',
            ],

            'customer' => [
                'id' => '编号',
                'user_name' => '用户名',
                'id_license' => '身份证号码',
                'birthday' => '生日',
                'department' => '部门',
                'telephone' => '电话',
                'consume_category' => '消费类别',
                'card' => 'IC卡',
                'new_card' => '新IC卡',
                'balance' => '余额',
                'subsidy_balance' => '补贴余额',
                'source' => '类型',
                'type' => '补贴人群',
                'money' => '充值金额',
                'pay_method' => '支付方式',
            ],

            'goods' => [
                'image' => '图片',
                'name' => '商品名',
                'price' => '价格',
                'shop_id' => '所属小店',
                'dinning_time_id' => '用餐时间',
            ],

            'labelCategory' => [
                'image' => '图片',
                'name' => '类别名',
            ],

            'consumeCategory' => [
                'name' => '类别名'
            ],

            'consumeRule' => [
                'name' => '类别名',
                'dinning_time' => '用餐时间',
                'weekday' => '日期',
                'consumeCategory' => '消费类别',
                'discount' => '折扣'
            ],

            'payMethod' => [
                'alipay' => [
                    'app_id' => '支付宝应用ID（APPID）',
                    'pid' => '合作伙伴身份（PID）',
                    'alipay_public_key' => '支付宝公钥',
                    'mch_private_key' => '应用私钥'
                ],

                'wechat_pay' => [
                    'app_id' => '应用ID（APPID）',
                    'mch_id' => '微信支付商户号',
                    'mch_api_key' => 'API 密钥',
                    'ssl_cert' => 'API 证书',
                    'ssl_key' => 'API 证书密钥',
                ]
            ],

            'consumeOrder' => [
                'order_id' => '订单编号',
                'customer' => '用户名',
                'card' => '卡号',
                'consume_category' => '消费类别',
                'goods_name' => '商品名',
                'price' => '原价',
                'label' => '标签',
                'discount_price' => '实付金额',
                'goods' => '商品',
                'pay_method' => '支付方式',
                'status' => '状态',
                'external_pay_no' => '支付号',
                'restaurant_user' => '操作用户'
            ]
        ],

        'frontend' => [
            'email'                     => '电子邮件',
            'username'                  => '用户名',
            'name'                      => '用户名',
            'password'                  => '密码',
            'password_confirmation'     => '确认密码',
            'phone' => 'Phone',
            'message' => 'Message',
            'old_password'              => '旧密码',
            'new_password'              => '新密码',
            'new_password_confirmation' => '确认新密码',
        ],
    ],

];
