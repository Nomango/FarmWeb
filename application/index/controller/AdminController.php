<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 10:32
 */

namespace app\index\controller;


use think\Controller;
use app\common\Model\Admin;
use app\common\Model\User;
use app\common\model\Goods;
use app\common\model\Order;
use think\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // 调用父类构造函数(必须)
        parent::__construct();

        // 验证用户是否登陆
        if (!Admin::isLogin()) {
            return $this->error('请先登陆', url('AdminLogin/index'));
        }
    }

    public function index()
    {
        $pageSize = 5; // 每页显示5条数据

        $Order = new Order;
        $orders = $Order->with(['user','goods'])->paginate($pageSize);
        $this->assign('orders', $orders);
        return $this->fetch();
    }

    public function usermanage()
    {
        $pageSize = 5; // 每页显示5条数据

        $User = new User;
        $users = $User->paginate($pageSize);
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function goodsmanage()
    {
        $pageSize = 5; // 每页显示5条数据

        $Goods = new Goods;
        $goods = $Goods->with(['category','image'])->paginate($pageSize);
        $this->assign('goods', $goods);
        return $this->fetch();
    }

    public function deleteUser()
    {
        $id = Request::instance()->param('id/d'); // “/d”表示将数值转化为“整形”

        if (is_null($id) || 0 === $id) {
            return json(['result' => false, 'msg' => '删除失败！未获取到ID信息！']);
        }

        $user = User::get($id);

        if (!is_null($user)) {
            if ($user->delete()) {
                return json(['result' => true, 'msg' => '删除成功！']);
            }
        }
        return json(['result' => false, 'msg' => '删除失败！']);
    }
}