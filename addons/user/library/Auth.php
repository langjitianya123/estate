<?php

namespace addons\user\library;

use addons\user\model\User;
use fast\Random;
use think\Cookie;
use think\Db;
use think\Exception;
use think\Request;
use think\Validate;

/**
 * Auth类
 */
class Auth
{

    protected static $instance = null;
    private $_error = '';
    private $_logined = FALSE;
    private $_user = NULL;
    private $keeptime = 0;
    private $requestUri = '';

    public function __construct()
    {
        $this->_user = new User;
    }

    /**
     * 初始化
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 
     * @return User
     */
    public function getModel()
    {
        return $this->_user;
    }

    /**
     * 兼容调用user模型的属性
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->check() ? $this->_user->$name : NULL;
    }

    /**
     * 兼容调用user模型的方法
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_user, $name], $arguments);
    }

    /**
     * 注册用户
     *
     * @param string $username  用户名
     * @param string $password  密码
     * @param string $email     邮箱
     * @param string $mobile    手机号
     * @param string $extend    扩展参数
     * @return boolean
     */
    public function register($username, $password, $email = '', $mobile = '', $extend = [], $keeptime = 0, $sync = TRUE)
    {
        // 检测用户名或邮箱、手机号是否存在
        if (User::getByUsername($username))
        {
            $this->setError('Username already exist');
            return FALSE;
        }
        if ($email && User::getByEmail($email))
        {
            $this->setError('Email already exist');
            return FALSE;
        }
        if ($mobile && User::getByMobile($mobile))
        {
            $this->setError('Mobile already exist');
            return FALSE;
        }

        $ip = request()->ip();
        $time = time();
        $config = get_addon_config('user');

        $data = [
            'username' => $username,
            'password' => $password,
            'email'    => $email,
            'mobile'   => $mobile,
            'level'    => $config['level'],
            'score'    => $config['score'],
            'avatar'   => $config['avatar'],
        ];
        $params = array_merge($data, [
            'nickname'  => $username,
            'salt'      => Random::alnum(),
            'jointime'  => $time,
            'joinip'    => $ip,
            'logintime' => $time,
            'loginip'   => $ip,
            'prevtime'  => $time,
            'status'    => 'normal'
        ]);
        $params['password'] = $this->getEncryptPassword($password, $params['salt']);
        $params = array_merge($params, $extend);

        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS && $sync)
        {
            $uc = new \addons\ucenter\library\client\Client();
            $user_id = $uc->uc_user_register($username, $password, $email);
            // 如果小于0则说明发生错误
            if ($user_id <= 0)
            {
                $this->setError($user_id > -4 ? 'Username is incorrect' : 'Email is incorrect');
                return FALSE;
            }
            else
            {
                $params['id'] = $user_id;
            }
        }

        //账号注册时需要开启事务,避免出现垃圾数据
        Db::startTrans();
        try
        {
            $ret = $this->_user->save($params);
            Db::commit();

            // 此时的Model中只包含部分数据
            $this->_user = $this->_user->get($this->_user->id);

            $this->keeptime($keeptime);
            return $this->syncLogin();
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            Db::rollback();
            return FALSE;
        }
    }

    /**
     * 用户登录
     *
     * @param string    $account    账号,用户名、邮箱、手机号
     * @param string    $password   密码
     * @param int       $keeptime   有效时长,默认为浏览器关闭
     * @return array
     */
    public function login($account, $password, $keeptime = 0, $sync = TRUE)
    {
        $field = Validate::is($account, 'email') ? 'email' : (Validate::regex($account, '/^1\d{10}$/') ? 'mobile' : 'username');
        $user = $this->_user->get([$field => $account]);
        if ($user)
        {
            if ($user->status != 'normal')
            {
                $this->setError('Account is locked');
                return FALSE;
            }
            if ($user->password != $this->getEncryptPassword($password, $user->salt))
            {
                $this->setError('Password is incorrect');
                return FALSE;
            }

            $this->_user = $user;

            // 设置登录有效时长
            $this->keeptime($keeptime);

            return $this->syncLogin($sync);
        }
        else
        {
            $this->setError('Account is incorrect');
            return FALSE;
        }
    }

    /**
     * 注销登录退出
     * @return bool
     */
    public function logout($token = NULL)
    {
        //设置登录标识
        $this->_logined = FALSE;
        $token = is_null($token) ? Cookie::get('token') : $token;
        Cookie::delete('user_id');
        //Cookie::del('username');
        Cookie::delete('token');
        return TRUE;
    }

    /**
     * 修改密码
     * @param string $newpassword 新密码
     * @param string $oldpassword 旧密码
     * @param bool $ignoreoldpassword 忽略旧密码
     * @return boolean
     */
    public function changepwd($newpassword, $oldpassword = '', $ignoreoldpassword = false)
    {
        if (!$this->check())
        {
            $this->setError('You are not logged in');
            return false;
        }
        //判断旧密码是否正确
        if ($this->_user->password == $this->getEncryptPassword($oldpassword, $this->_user->salt) || $ignoreoldpassword)
        {
            ////////////////同步到Ucenter////////////////
            if (defined('UC_STATUS') && UC_STATUS)
            {
                $uc = new \addons\ucenter\library\client\Client();
                $ret = $uc->uc_user_edit($this->_user->id, $this->_user->authname, $newpassword, $this->_user->email, $this->_user->mobile);
                // 如果小于0则说明发生错误
                if ($ret < 0)
                {
                    $this->setError('Change password failure');
                }
            }

            $salt = Random::alnum();
            $newpassword = $this->getEncryptPassword($newpassword, $salt);
            $this->_user->save(['password' => $newpassword, 'salt' => $salt]);
            return true;
        }
        else
        {
            $this->setError('Password is incorrect');
            return false;
        }
    }

    /**
     * 初始化
     *
     * @param int       $user_id    会员ID,默认从Cookie中取
     * @param string    $token      会员Token,默认从Cookie中取
     *
     * @return boolean
     */
    public function init($user_id = NULL, $token = NULL)
    {
        $user_id = $user_id ? $user_id : Cookie::get('user_id');
        $user_id = intval($user_id);
        if ($user_id > 0)
        {
            if ($this->_error)
                return FALSE;
            $user = $this->_user->get($user_id);
            if (!$user)
            {
                $this->setError('Account not exist');
                return FALSE;
            }
            if ($user['status'] != 'normal')
            {
                $this->setError(self::ERR_ACCOUNT_IS_LOCKED);
                return FALSE;
            }
            $token = $token ? $token : Cookie::get('token');
            if (!$user['token'] || $token !== $user['token'])
            {
                return FALSE;
            }
            $this->_user = $user;
            $this->_logined = TRUE;
            return TRUE;
        }
        else
        {
            $this->setError('You are not logged in');
            return FALSE;
        }
    }

    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function check()
    {
        return $this->_logined;
    }

    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function isLogin()
    {
        return $this->check();
    }

    /**
     * 获取当前请求的URI
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }

    /**
     * 删除一个指定会员
     * @param int $user_id
     * @param bool $sync 是否同步删除
     */
    public function delete($user_id, $sync = TRUE)
    {
        $user = $this->_user->get($user_id);
        if (!$user)
        {
            return FALSE;
        }

        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS && $sync)
        {
            $uc = new \addons\ucenter\library\client\Client();
            $re = $uc->uc_user_delete($user['id']);
            // 如果小于0则说明发生错误
            if ($re <= 0)
            {
                $this->setError(self::ERR_ACCOUNT_IS_LOCKED);
                return FALSE;
            }
        }
        // 调用事务删除账号
        $result = Db::transaction(function($db) use($user_id) {
                    // 删除会员
                    User::destroy($user_id);
                });

        return $result ? TRUE : FALSE;
    }

    /**
     * 直接登录账号
     * @param int $user_id
     * @param boolean $sync
     * @return boolean
     */
    public function direct($user_id, $sync = TRUE)
    {
        $this->_user = $this->_user->get($user_id);
        if ($this->_user)
        {
            $this->syncLogin($sync);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * 获取密码加密方式
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function getEncryptPassword($password, $salt = '')
    {
        return md5(md5($password) . $salt);
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     */
    public function match($arr = [])
    {
        $request = Request::instance();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr)
        {
            return FALSE;
        }
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr))
        {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }

    /**
     * 同步登录信息
     * @param int $sync     是否同步登录到UC
     * @return boolean
     */
    protected function syncLogin($sync = TRUE)
    {
        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS && $sync)
        {
            $uc = new \addons\ucenter\library\client\Client();
            $re = $uc->uc_user_login($this->_user->id, $this->_user->password . '#split#' . $this->_user->salt, 3);
            // 如果小于0则说明发生错误
            if ($re <= 0)
            {
                $this->setError('Username or password is incorrect');
                return FALSE;
            }
        }

        //增加登录次数和设置最后登录时间
        $this->_user->save([
            'prevtime'  => $this->_user->logintime,
            'logintime' => time(),
            'token'     => Random::uuid(),
            'loginip'   => request()->ip(),
        ]);

        // 写入登录Cookies和Token
        $this->writeStatus();
        return TRUE;
    }

    /**
     * 写入登录态和Cookie
     *
     * @param int $keeptime
     */
    protected function writeStatus()
    {
        //设置登录标识
        $this->_logined = TRUE;

        Cookie::set('user_id', $this->_user->id, $this->keeptime);
        Cookie::set('username', $this->_user->username, 86400 * 365);
        //加密安全字符
        Cookie::set('token', $this->_user->token, $this->keeptime);
        $this->setError('');
    }

    /**
     * 设置会话有效时间
     * @param int $keeptime 默认为永久
     */
    public function keeptime($keeptime = 0)
    {
        $this->keeptime = $keeptime;
    }

    /**
     * 渲染用户数据
     * @param array     $datalist
     * @param array     $fields
     * @param string    $fieldkey
     * @param string    $renderkey
     * @return array
     */
    public function render(&$datalist, $fields = [], $fieldkey = 'user_id', $renderkey = 'userinfo')
    {
        $fields = !$fields ? ['id', 'nickname', 'level', 'avatar'] : (is_array($fields) ? $fields : explode(',', $fields));
        $ids = [];
        foreach ($datalist as $k => $v)
        {
            if (!isset($v[$fieldkey]))
                continue;
            $ids[] = $v[$fieldkey];
        }
        $list = [];
        if ($ids)
        {
            if (!in_array('id', $fields))
            {
                $fields[] = 'id';
            }
            $ids = array_unique($ids);
            $selectlist = User::where('id', 'in', $ids)->column($fields);
            foreach ($selectlist as $k => $v)
            {
                $list[$v['id']] = $v;
            }
        }
        foreach ($datalist as $k => &$v)
        {
            $v[$renderkey] = isset($list[$v[$fieldkey]]) ? $list[$v[$fieldkey]] : NULL;
        }
        unset($v);
        return $datalist;
    }

    /**
     * 设置错误信息
     *
     * @param $error
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return __($this->_error);
    }

}
