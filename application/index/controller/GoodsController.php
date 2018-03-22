<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/21
 * Time: 15:12
 */

namespace app\index\controller;


use app\common\model\Goods;
use app\common\model\Order;
use app\common\model\User;
use think\Controller;
use think\exception\DbException;
use think\Request;

class GoodsController extends Controller
{
    public function index()
    {
        $postData = Request::instance()->param();
        if (isset($postData['id'])) {
            $goods = Goods::where('category_id', '=', $postData['id'])->paginate(12);
            $id = $postData['id'];
        } else {
            $goods = Goods::where('category_id', '=', 1)->paginate(12);
            $id = 1;
        }
        // 传递用户信息
        if (User::isLogin()) {
            $user = User::getInfo();
            $this->assign('user', $user);
        }
        $this->assign([
            'isHall' => true,
            'category_id' => $id,
            'goods' => $goods,
        ]);
        return $this->fetch();
    }

    public function more()
    {
        $category_id = Request::instance()->param('category_id/d');
        $goods = Goods::where('category_id', '=', $category_id)->paginate(12);
        return json([
            'data'=>$goods->items(),
            'pages'=>$goods->lastPage()
        ]);
    }

    public function detail()
    {
        $goods_id = Request::instance()->param('id/d');
        try {
            $goods = Goods::get($goods_id);
            // 获取相关推荐
            $goods_recommoned = Goods::where('category_id', '=', $goods['category_id'])
                ->limit(0 , 12)
                ->select();
            $this->assign([
                'goods' => $goods,
                'goods_recommoned' => $goods_recommoned
            ]);
        } catch (DbException $e) {
            $this->redirect('index');
            return null;
        }
        // 传递用户信息
        if (User::isLogin()) {
            $user = User::getInfo();
            $this->assign('user', $user);
        }
        return $this->fetch();
    }

    public function purchase()
    {
        //获取购买商品的id
        $id = Request::instance()->param('id/d');
        $number = Request::instance()->param('number/d');
        $goods = Goods::where('id','=',$id)->find();
        $total = $number * $goods['price'];
        // 传递用户信息
        if (User::isLogin()) {
            $user = User::getInfo();
            $this->assign([
                'number' =>$number,
                'goods' => $goods,
                'user' => $user,
                'total' =>$total
            ]);
            $this->assign('user', $user);
        }
        else{
           redirect(url('Login/index'))->remember();
        }
        return $this->fetch();
    }

    public function insertorder(){
        $order = new Order();
        $id = Request::instance()->param('id/d');
        $number = Request::instance()->param('number/d');
        $user = User::getInfo();
        $goods = Goods::where('id','=',$id)->find();
        if($number > $goods['number']){
            return json(['result'=>false, 'msg'=>'抱歉，库存不足！']);
        }
        $newNumber = $goods['number'] - $number;
        Goods::where('id','=',$id)->setField('number',$newNumber);
        $total = $number * $goods['price'];
        $order['user_id'] = $user['id'];
        $order['goods_id'] = $id;
        $order['price'] = $goods['price'];
        $order['number'] = $number;
        $order['total'] = $total;
        $order['state'] = '0';
        if( $order->save()){
            return json(['result'=>true]);
        }
        else{
            return json(['result'=>false,'msg'=>'支付失败']);
        }
    }
}