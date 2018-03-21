<?php
/**
 * Created by PhpStorm.
 * User: locust
 * Date: 2018/3/18
 * Time: 10:25
 */

namespace app\index\controller;


use think\Controller;
use app\common\model\User;

class UserController extends Controller
{
    public function __construct()
    {
        // 调用父类构造函数(必须)
        parent::__construct();

        // 验证用户是否登陆
        if (!User::isLogin()) {
            return $this->redirect(url('Login/index'));
        }
    }

    public function index()
    {
        $user = User::getInfo();
        $this->assign([
            'user' => $user,
        ]);
        return $this->fetch();
    }

    public function address()
    {
        $user = User::getInfo();
        $this->assign([
            'user' => $user,
        ]);
        return $this->fetch();
    }

    public function orders()
    {
        $user = User::getInfo();
        $this->assign([
            'user' => $user,
        ]);
        return $this->fetch();
    }
}