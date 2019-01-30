<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */

    'backend' => [
        'roles' => [
            'created' => '角色已成功创建。',
            'deleted' => '角色已成功删除。',
            'updated' => '角色已成功更新。',
        ],

        'users' => [
            'cant_resend_confirmation' => 'The application is currently set to manually approve users.',
            'confirmation_email'  => '新的确认电子邮件已发送到文件上的地址。',
            'confirmed'              => 'The user was successfully confirmed.',
            'created'             => '用户已成功创建。',
            'deleted'             => '用户已成功删除。',
            'deleted_permanently' => '用户被永久删除。',
            'restored'            => '用户已成功还原。',
            'session_cleared'     => '用户会话已成功清除。',
            'social_deleted' => 'Social Account Successfully Removed',
            'unconfirmed' => 'The user was successfully un-confirmed',
            'updated'             => '用户已成功更新。',
            'updated_password'    => '用户密码已成功更新。',
        ],

        'department' => [
            'created' => '部门添加成功.',
            'updated' => '部门更新成功.',
            'imported' => '部门导入成功.',
        ],

        'card' => [
            'created' => 'IC卡添加成功.',
            'updated' => 'IC卡更新成功.',
            'imported' => 'IC卡导入成功.',
        ],

        'shop' => [
            'created' => '子商户添加成功.',
            'updated' => '子商户更新成功.',
            'imported' => '子商户导入成功.',
        ],

        'dinningTime' => [
            'created' => '用餐时间添加成功.',
            'updated' => '用餐时间更新成功.',
            'imported' => '用餐时间导入成功.',
        ],

        'customer' => [
            'created' => '卡用户添加成功.',
            'updated' => '卡用户更新成功.',
            'updated_balance' => '余额更新成功',
            'binded' => '卡绑定成功',
            'unbinded' => '卡解绑成功',
            'lost' => '卡挂失成功',
        ],

        'goods' => [
            'created' => '商品添加成功.',
            'updated' => '商品更新成功.',
            'deleted' => '商品删除成功.',
            'labelCategoryAssigned' => '餐盘类别分配成功',
        ],

        'labelCategory' => [
            'created' => '餐盘类别添加成功.',
            'updated' => '餐盘类别更新成功.',
            'deleted' => '餐盘类别删除成功.',
            'labelAssigned' => '餐盘分配成功',
        ],

        'consumeCategory' => [
            'created' => '消费类别添加成功.',
            'updated' => '消费类别更新成功.',
        ],

        'consumeRule' => [
            'created' => '消费规则添加成功.',
            'updated' => '消费规则更新成功.',
            'deleted' => '消费规则删除成功.',
        ],

        'payMethod' => [
            'updated' => '支付方式更新成功.',
        ],
    ],

    'frontend' => [
        'contact' => [
            'sent' => 'Your information was successfully sent. We will respond back to the e-mail provided as soon as we can.',
        ],
    ],
];
