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
use think\Request;

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
        if($user['address']==""){
            $this->assign([
               'user' =>$user,
               'msg' =>'1'
            ]);
            return $this->fetch();
        }
        $this->assign([
            'user' => $user,
            'msg' =>'2'
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

    public function resetaddress(){
        $user = User::getInfo();
        $user['nickname'] = Request::instance()->param('nickname');
        $user['area'] = Request::instance()->param('area');
        $user['address'] = Request::instance()->param('address');
        $user['zipcode'] = Request::instance()->param('zipcode');
        $user['phone'] = Request::instance()->param('phone');
        $user->validate()->save();
        return json([
           'msg' =>"修改成功"
        ]);
    }

    public function resetinfo(){
        $user = User::getInfo();
        $user['nickname'] = Request::instance()->param('nickname');
        $user['sex'] = Request::instance()->param('sex');
        $user['email'] = Request::instance()->param('email');
        $user->validate()->save();
        return json([
            'code' =>'1'
        ]);
    }
}