<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\User;
use app\common\model\Goods;

class IndexController extends Controller
{
    public function index()
    {
        // 检查用户是否登录
        $user = null;
        if (User::isLogin()) {
            $user = User::getInfo();
        }
        // 获取商品信息
        $goods = Goods::with(['category']);
        $goods1 = $goods->where('category_id', '=', '1')->order('id desc')->limit('0', '12')->select();
        $goods2 = $goods->where('category_id', '=', '2')->order('id desc')->limit('0', '12')->select();
        $goods3 = $goods->where('category_id', '=', '3')->order('id desc')->limit('0', '12')->select();
        $goods = array_merge($goods1, $goods2, $goods3);
        $this->assign([
            'user' => $user,
            'goods' => $goods,
            'isIndex' => true
        ]);
        return $this->fetch();
    }

    protected function insert()
    {
        for ($i = 0; $i < 20; $i++)
        {
            $goods = new Goods();
            $goods['name'] = '旅游产品'.$i;
            $goods['price'] = rand(0, 5000) + 2000;
            $goods['number'] = rand(50, 120);
            $goods['image'] = 'image_rect.png';
            $goods['category_id'] = 1;

            $goods->save();
        }

        for ($i = 0; $i < 20; $i++)
        {
            $goods = new Goods();
            $goods['name'] = '手工制品'.$i;
            $goods['price'] = rand(0, 300) + 30;
            $goods['number'] = rand(50, 120);
            $goods['image'] = 'image_rect.png';
            $goods['category_id'] = 2;

            $goods->save();
        }

        for ($i = 0; $i < 20; $i++)
        {
            $goods = new Goods();
            $goods['name'] = '农副产品'.$i;
            $goods['price'] = rand(0, 100) + 20;
            $goods['number'] = rand(50, 120);
            $goods['image'] = 'image_rect.png';
            $goods['category_id'] = 3;

            $goods->save();
        }

        return null;
    }
}
