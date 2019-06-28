<?php

namespace addons\third\library;

use addons\third\model\Third;
use fast\Random;

/**
 * 第三方登录服务类
 *
 * @author Karson
 */
class Service
{

    /**
     * 第三方登录
     * @param string    $platform
     * @param array     $params
     * @param int       $keeptime
     * @return boolean
     */
    public static function connect($platform, $params = [], $keeptime = 0)
    {
        $time = time();
        $values = [
            'platform'      => $platform,
            'openid'        => $params['openid'],
            'openname'      => isset($params['userinfo']['nickname']) ? $params['userinfo']['nickname'] : '',
            'access_token'  => $params['access_token'],
            'refresh_token' => $params['refresh_token'],
            'expires_in'    => $params['expires_in'],
            'logintime'     => $time,
            'expiretime'    => $time + $params['expires_in'],
        ];
        $auth = \addons\user\library\Auth::instance();

        $auth->keeptime($keeptime);
        $third = Third::get(['platform' => $platform, 'openid' => $params['openid']]);
        if ($third)
        {
            $user = \addons\user\model\User::get($third['user_id']);
            if (!$user)
            {
                return FALSE;
            }
            $third->save($values);
            return $auth->direct($user->id);
        }
        else
        {
            // 先随机一个用户名,随后再变更为u+数字id
            $username = Random::alnum(20);
            $password = Random::alnum(6);
            // 默认注册一个会员
            $result = $auth->register($username, $password, '', '', [], $keeptime);
            if (!$result)
            {
                return FALSE;
            }
            $user = $auth->getModel();
            $userarr = ['username' => 'u' . $user->id];
            if (isset($params['userinfo']['nickname']))
                $userarr['nickname'] = $params['userinfo']['nickname'];
            if (isset($params['userinfo']['avatar']))
                $userarr['avatar'] = $params['userinfo']['avatar'];

            // 更新会员资料
            $user->save($userarr);

            // 保存第三方信息
            $values['user_id'] = $user->id;
            Third::create($values);

            // 写入登录Cookies和Token
            return $auth->direct($user->id);
        }
    }

}
