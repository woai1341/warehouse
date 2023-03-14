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
    Route::get('productData', 'CommController/getAllProductData'); // 仓库现有产品数据
    // 登录
    Route::group("login", function (){
        Route::get("home", "LoginController/home"); //首页信息
        Route::post("userLogin", "LoginController/login"); //用户登录
        Route::any('userLogout', "LoginController/logout"); //退出登录
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
    // 仓库列表
    Route::group("product", function(){
        Route::get("list", "ProductController/index"); // 仓库列表页面
        Route::get("getData", "ProductController/getListData"); //仓库列表数据接口
        Route::any("add", "ProductController/add"); //添加数据
        Route::get("edit/:id", "ProductController/editHtml"); //编辑页面
        Route::post("edit/:id", "ProductController/update"); //编辑数据
        Route::post("deleted","ProductController/del");
        Route::post('out/:id', "ProductController/outOfStock"); //出库
        Route::get('out_html/:id', "ProductController/outHTML");    //出库页面
        Route::get("showTime/:id", "ProductController/showTime"); //出库时间列表详情
        Route::get('echarts/:id', "ProductController/getEchartsLines");
    });
    // 销售记录
    Route::group("sale_list", function(){
        Route::get("list", "SaleController/index"); // 仓库列表页面
        Route::get("getData", "SaleController/getListData"); //仓库列表数据接口
        Route::get("add", "SaleController/add_html"); //add 页面
        Route::post("explode", "SaleController/explode"); //导出
    });
    
    
});
