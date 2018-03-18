<?php
/**
 * Created by PhpStorm.
 * User: locust
 * Date: 2018/3/18
 * Time: 10:25
 */

namespace app\index\controller;


use think\Controller;

class UserController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function address()
    {
        return $this->fetch();
    }
}