<?php

return array (
  0 => 
  array (
    'name' => 'qq',
    'title' => 'QQ',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'scope' => 'get_user_info',
      'callback' => 'http://www.fastadmin.net/callback',
    ),
    'value' => 
    array (
      'app_id' => 'qq app_id',
      'app_secret' => 'qq app_secret',
      'scope' => 'get_user_info',
      'callback' => 'your callback url',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'wechat',
    'title' => '微信',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'callback' => '',
      'scope' => 'snsapi_userinfo',
    ),
    'value' => 
    array (
      'app_id' => 'wechat app_id',
      'app_secret' => 'wechat app_secret',
      'scope' => 'get_user_info',
      'callback' => 'your callback url',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'weibo',
    'title' => '微博',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'callback' => '',
    ),
    'value' => 
    array (
      'app_id' => 'weibo app_id',
      'app_secret' => 'weibo app_secret',
      'scope' => 'get_user_info',
      'callback' => 'your callback url',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);