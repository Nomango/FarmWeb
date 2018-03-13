<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 16:38
 */

namespace app\index\controller;


use think\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function register()
    {
        $post = Request::instance()->post();

        $User = new User();
        $userData = $User->data($post);

        // 向user表中插入数据并判断是否插入成功
        $result = $User->validate(true)->save($userData->getData());
        if ($result) {
            return '新增用户成功，用户名为'.$User['nickname'].'，新增用户ID为'.$User['id'];
        }
        else {
            return '新增失败';
        }
    }
}