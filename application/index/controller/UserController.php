<?php
/**
 * Created by PhpStorm.
 * User: locust
 * Date: 2018/3/18
 * Time: 10:25
 */

namespace app\index\controller;


use app\common\model\Order;
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
        $orders = Order::with(['goods'])->where('user_id','=',$user['id'])->order('create_time desc')->paginate('5','true');
       // return json($orders);
        $this->assign([
            'user' => $user,
            'orders' =>$orders
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


    public function confirmorder(){
        $id = Request::instance()->param('id/d');
        $order = Order::where('id','=',$id)->find();
        if($order['state']==0){
            Order::where('id','=',$id)->setField('state',2);
            return json([
               'code' => '1',
               'msg' => '收货成功'
            ]);
        }
        else if($order['state']==1){
            Order::where('id','=',$id)->setField('state',2);
            return json([
                'code' => '2',
                'msg' => '收货成功'
            ]);
        }
        return json([
            'code' => '3',
            'msg' => '改单已完成'
        ]);
    }

    public function deleteorder(){
        $id = Request::instance()->param('id/d');
        $order = Order::where('id','=',$id)->find();
        if(!$order){
            return json([
                'result' => false,
                'msg' => '该订单不存在'
            ]);
        }
        Order::where('id','=',$id)->delete();
        return json([
           'result' => true,
           'msg' =>'删除成功'
        ]);
    }
}