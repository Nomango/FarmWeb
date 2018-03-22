<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/21
 * Time: 15:12
 */

namespace app\index\controller;


use app\common\model\Goods;
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
        // 传递用户信息
        if (User::isLogin()) {
            $user = User::getInfo();
            $this->assign('user', $user);
        }
        return $this->fetch();
    }
}