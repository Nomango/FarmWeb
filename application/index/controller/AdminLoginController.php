<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 16:08
 */

namespace app\index\controller;


use think\Controller;
use think\Request;
use app\common\Model\Admin;

class AdminLoginController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        // 接收post信息
        $postData = Request::instance()->post();
        // 登陆
        $result = Admin::login($postData['account'], $postData['password']);
        // 根据不同结果跳转不同页面
        if ($result) {
            return $this->success('登陆成功', url('Admin/index'));
        } else {
            return $this->error('用户名不存在', url('index'));
        }
    }

    // 注销
    public function logOut()
    {
        if (Admin::logOut()) {
            return $this->success('注销成功', url('index'));
        } else {
            return $this->error('注销异常', url('index'));
        }
    }

    public function test()
    {
        echo Admin::encryptPassword('admin');
    }
}