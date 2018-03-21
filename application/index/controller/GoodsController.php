<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/21
 * Time: 15:12
 */

namespace app\index\controller;


use app\common\model\Goods;
use think\Controller;
use think\exception\DbException;
use think\Request;

class GoodsController extends Controller
{
    public function index()
    {
        $this->assign([
            'isHall' => true
        ]);
        return $this->fetch();
    }

    public function detail()
    {
        $goods_id = Request::instance()->param('id/d');
        try {
            $goods = Goods::get($goods_id);
            $this->assign([
                'goods' => $goods,
            ]);
        } catch (DbException $e) {
            $this->redirect('index');
            return null;
        }
        return $this->fetch();
    }
}