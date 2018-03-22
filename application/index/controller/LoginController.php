<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 9:28
 */

namespace app\index\controller;


use think\Controller;
use app\common\model\User;
use think\Request;

class LoginController extends Controller
{
    // 用户登陆表单
    public function index()
    {
        if (User::isLogin()) {
            $this->redirect(url('Index/index'));
        } else {
            // 显示登陆表单
            return $this->fetch();
        }
    }

    public function login()
    {
        // 接收post信息
        $postData = Request::instance()->post();
        // 登陆
        $result = User::login($postData['username'], $postData['password']);
        // 根据不同结果跳转不同页面
        if ($result) {
            return json(['result' => true]);
        } else {
            return json(['result' => false]);
        }
    }

    // 注销
    public function logOut()
    {
        User::logOut();
    }

    public function test()
    {
        return User::encryptPassword('123456');
    }
}