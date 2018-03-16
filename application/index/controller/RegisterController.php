<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 16:38
 */

namespace app\index\controller;


use think\Controller;
use think\Request;
use app\common\model\User;

class RegisterController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function register()
    {
        $post = Request::instance()->post();

        // 验证用户是否存在
        $checkuser = User::get(['username' => $post['username']]);
        if (!is_null($checkuser)) {
            return json(['result'=>false, 'msg'=>'该账号已被注册']);
        }

        $User = new User();
        $userData = $User->data($post);
        $userData['password'] = User::encryptPassword($userData['password']);

        // 向user表中插入数据并判断是否插入成功
        $result = $User->validate()->save($userData->getData());
        if ($result) {
            return json(['result'=>true]);
        }
        else {
            return json(['result'=>false, 'msg'=>'注册出现未知错误']);
        }
    }
}