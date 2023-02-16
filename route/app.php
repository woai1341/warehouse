<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
//登录
Route::get('/', 'Index/index');

Route::group("admin", function (){
    Route::get("welcome", "Index/welcome");
    // 登录
    Route::group("login", function (){
        Route::get("home", "LoginController/home"); //首页信息
        Route::post("userLogin", "LoginController/login"); //用户登录
    });
    // 客户
    Route::group("customers", function (){
        Route::get("list", "CustomersController/index");  //客户列表
        Route::get("listData","CustomersController/getCustomersInfo");//客户列表数据
        Route::post("deleted","CustomersController/del");
        Route::get("edit/:id", "CustomersController/editHtml"); //编辑页面
        Route::post("edit/:id", "CustomersController/update"); //编辑数据
        Route::any("add", "CustomersController/add"); //添加数据
    });
    // 首页
    Route::group("category", function(){
        Route::get("list", "HomeController/index");  //首页列表
    });
    
});
