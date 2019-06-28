<?php

namespace addons\wechat\library;

/**
 * Wechat配置类
 */
class Config
{

    public function __construct()
    {
        
    }

    public static function load()
    {
        $config = get_addon_config('wechat');

        return [
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug'   => !!$config['debug'],
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => $config['app_id'], // AppID
            'secret'  => $config['secret'], // AppSecret
            'token'   => $config['token'], // Token
            'aes_key' => $config['aes_key'], // EncodingAESKey，安全模式下请一定要填写！！！
            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
             * file：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log'     => [
                'level'      => $config['log_level'],
                'permission' => 0777,
                'file'       => ROOT_PATH . '/runtime/log/easywechat.log',
            ],
            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth'   => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => $config['oauth_callback'],
            ],
            /**
             * 微信支付
             */
            'payment' => [
                'merchant_id' => 'your-mch-id',
                'key'         => 'key-for-signature',
                'cert_path'   => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'    => 'path/to/your/key', // XXX: 绝对路径！！！！
            // 'device_info'     => '013467007045764',
            // 'sub_app_id'      => '',
            // 'sub_merchant_id' => '',
            // ...
            ],
            /**
             * Guzzle 全局设置
             *
             * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
             */
            'guzzle'  => [
                'timeout' => 3.0, // 超时时间（秒）
            //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ],
        ];
    }

}
