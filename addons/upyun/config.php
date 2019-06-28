<?php

return array(
    'bucket'    =>
    array(
        'name'    => 'bucket',
        'title'   => 'bucket',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => 'your bucket',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'cdnurl'    =>
    array(
        'name'    => 'cdnurl',
        'title'   => 'CDN地址',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => 'http://yourcdnurl.com',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'uploadurl' =>
    array(
        'name'    => 'uploadurl',
        'title'   => '上传接口地址',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => 'http://v0.api.upyun.com/yourbucket',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'notifyurl' =>
    array(
        'name'    => 'notifyurl',
        'title'   => '回调通知地址',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => 'http://yoursite.com/addons/upyun/index/notify',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'formkey'   =>
    array(
        'name'    => 'formkey',
        'title'   => '表单密钥',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => 'your formkey',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'savekey'   =>
    array(
        'name'    => 'savekey',
        'title'   => '保存文件名',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => '/uploads/{year}{mon}{day}/{filemd5}{.suffix}',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'expire'    =>
    array(
        'name'    => 'expire',
        'title'   => '上传有效时长',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => '600',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'maxsize'   =>
    array(
        'name'    => 'maxsize',
        'title'   => '最大可上传',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => '10M',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'mimetype'  =>
    array(
        'name'    => 'mimetype',
        'title'   => '可上传后缀格式',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => '*',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    'multiple'  =>
    array(
        'name'    => 'multiple',
        'title'   => '多文件上传',
        'type'    => 'radio',
        'content' =>
        array(
            0 => '否',
            1 => '是',
        ),
        'value'   => '0',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
);
