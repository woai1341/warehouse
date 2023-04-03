<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index(): \think\response\View
    {
        return view("./login/login");
    }
    public function welcome(): \think\response\View
    {
        return view("./welcome");
    }
    
    
    
    public function test(){
        return "DJDR" . date("Y-m-d")  . time() . mt_rand(100000,999999);
    }
}
