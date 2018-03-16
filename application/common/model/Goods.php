<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/4
 * Time: 17:07
 */

namespace app\common\model;


use think\Model;

class Goods extends Model
{
    public function category()
    {
        return $this->belongsTo('category', 'category_id', 'id');
    }

    public function image()
    {
        return $this->hasOne('image', 'id', 'image_id');
    }
}