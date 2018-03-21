<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 10:32
 */

namespace app\index\controller;


use think\Controller;
use app\common\model\Admin;
use app\common\model\User;
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
        $Order = new Order;
        $orders = $Order->with(['user','goods'])->paginate(5,true);
        $this->assign([
            'orders'=> $orders,
            'index'=> 1
        ]);
        return $this->fetch();
    }

    public function usermanage()
    {
        $User = new User;
        $users = $User->where('delete', '=', 0)->paginate(5,true);
        $this->assign([
            'users'=> $users,
            'index'=> 2
        ]);
        return $this->fetch();
    }

    public function goodsmanage()
    {
        $Goods = new Goods;
        $goods = $Goods->with(['category'])->paginate(5,true);
        $this->assign([
            'goods'=> $goods,
            'index'=> 3
        ]);
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

    public function addGoods()
    {
        $postData = Request::instance()->param();
        $goods = new Goods();
        $goods['name'] = $postData['name'];
        $goods['price'] = $postData['price'];
        $goods['number'] = $postData['number'];
        $goods['image'] = $this->upload();
        $goods['category_id'] = $postData['category_id'];

        $goods->save();
        $this->redirect(url('Admin/goodsmanage'));
    }

    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'image');
            if($info){
                return $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        return "";
    }
}