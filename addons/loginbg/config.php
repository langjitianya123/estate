<?php

return [
    [
        //配置唯一标识
        'name'    => 'mode',
        //显示的标题
        'title'   => '模式',
        //类型
        'type'    => 'radio',
        //数据字典
        'content' => [
            'fixed'  => '固定',
            'random' => '每次随机',
            'daily'  => '每日切换',
        ],
        //值
        'value'   => 'fixed',
        //验证规则 
        'rule'    => 'required',
        //错误消息
        'msg'     => '',
        //提示消息
        'tip'     => '',
        //成功消息
        'ok'      => '',
        //扩展信息
        'extend'  => ''
    ],
    [
        //配置唯一标识
        'name'    => 'image',
        //显示的标题
        'title'   => '固定背景图',
        //类型
        'type'    => 'image',
        //数据字典
        'content' => [
        ],
        //值
        'value'   => '/assets/img/loginbg.jpg',
        //验证规则 
        'rule'    => 'required',
        //错误消息
        'msg'     => '',
        //提示消息
        'tip'     => '',
        //成功消息
        'ok'      => '',
        //扩展信息
        'extend'  => ''
    ],
];
