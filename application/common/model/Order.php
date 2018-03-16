<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/4
 * Time: 17:08
 */

namespace app\common\model;


use think\Model;

class Order extends Model
{
    public function user()
    {
        return $this->hasOne('user', 'id', 'user_id');
    }

    public function goods()
    {
        return $this->hasOne('goods', 'id', 'goods_id');
    }
}