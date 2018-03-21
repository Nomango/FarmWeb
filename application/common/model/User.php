<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/4
 * Time: 17:07
 */

namespace app\common\model;


use think\Model;
use think\Session;

class User extends Model
{
    protected $auto = ['create_time', 'update_time'];

    static public function login($username, $password)
    {
        // 验证用户是否存在
        $user = self::get(['username' => $username]);

        if (!is_null($user)) {
            // 验证密码是否正确
            if ($user->checkPassword($password)) {
                // 登录
                Session::set('userid', $user->getData('id'));
                // 记录登陆时间
                Session::set('logintime', time());
                return true;
            }
        }
        return false;
    }

    static public function logOut()
    {
        // 销毁session中数据
        Session::pull('userid');
        return true;
    }

    static public function isLogin()
    {
        $userId = Session::get('userid');
        $logintime = Session::get('logintime');

        if (isset($userId) && isset($logintime)) {
            // 一小时失效
            if ((time() - $logintime) > 3600) {
                Session::pull('logintime');
                Session::pull('userid');
            } else {
                return true;
            }
        }
        return false;
    }

    static public function getInfo()
    {
        if (self::isLogin()) {
            return self::get(Session::get('userid'));
        }
        return null;
    }

    public function checkPassword($password)
    {
        if ($this->getData('password') === $this::encryptPassword($password)) {
            return true;
        } else {
            return false;
        }
    }

    static public function encryptPassword($password)
    {
        if (!is_string($password)) {
            throw new \RuntimeException("传入变量类型非字符串，错误码2", 2);
        }
        // 实际的过程中，我还还可以借助其它字符串算法，来实现不同的加密。
        return sha1(md5($password) . 'farmweb');
    }
}