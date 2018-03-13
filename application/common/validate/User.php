<?php
/**
 * Created by PhpStorm.
 * User: Nomango
 * Date: 2018/3/11
 * Time: 14:08
 */

namespace app\common\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require|unique:user|length:4,25',
        'nickname'  => 'require|length:2,25',
        'password'  => 'require|length:4,16',
        'sex' => 'in:0,1',
        'email' => 'email',
    ];
}