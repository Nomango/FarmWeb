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

        // 实例化User
        $User = new User;

        // 按条件查询数据并调用分页
        $users = $User->paginate($pageSize);

        // 向V层传数据
        $this->assign('users', $users);

        // 将数据返回给用户
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