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
            return $this->redirect(url('AdminLogin/index'));
        }
    }

    public function index()
    {
        $pageSize = 5; // 每页显示5条数据

        $Order = new Order;
        $orders = $Order->with(['user','goods'])->paginate($pageSize);
        $this->assign('orders', $orders);
        return $this->fetch('admin/index');
    }

    public function usermanage()
    {
        $pageSize = 5; // 每页显示5条数据

        $User = new User;
        $users = $User->where('delete', '=', 0)->paginate($pageSize);
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function goodsmanage()
    {
        $pageSize = 5; // 每页显示5条数据

        $Goods = new Goods;
        $goods = $Goods->with(['category'])->paginate($pageSize);
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
            $user['delete'] = 1;
            if ($user->validate(true)->save()) {
                return json(['result' => true]);
            }
        }
        return json(['result' => false, 'msg' => '删除失败！找不到该用户或保存时出现错误']);
    }
}