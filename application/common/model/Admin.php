<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/4
 * Time: 17:08
 */

namespace app\common\model;


use think\Model;

class Admin extends Model
{
    static public function login($account, $password)
    {
        // 验证用户是否存在
        $admin = self::get(['account' => $account]);

        if (!is_null($admin)) {
            // 验证密码是否正确
            if ($admin->checkPassword($password)) {
                // 登录
                session('adminId', $admin->getData('id'));
                return true;
            }
        }
        return false;
    }

    static public function logOut()
    {
        // 销毁session中数据
        session('adminId', null);
        return true;
    }

    static public function isLogin()
    {
        $userId = session('adminId');

        // isset()和is_null()是一对反义词
        return isset($userId);
    }

    public function checkPassword($password)
    {
        if ($this->getData('password') === $this::encryptPassword($password))
        {
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