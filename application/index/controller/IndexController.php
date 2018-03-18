<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\User;

class IndexController extends Controller
{
    public function index()
    {
        $isLogin = User::isLogin();
        $user = null;
        if ($isLogin) {
            $user = User::getInfo();
        }
        $this->assign([
            'isLogin' => $isLogin,
            'user' => $user
        ]);
        return $this->fetch();
    }
}
