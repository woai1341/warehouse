<?php

namespace app\controller;

use app\BaseController;
use app\model\OrderSaleList;

class OrderController extends BaseController
{
    // 订单列表首页
    public function index(){
        return view();
    }
    
    public function getIndexData(){
        $page = input("page", 1);
        $limit = input("limit", 10);
        $name = input('name');          //产品名称
        $trademark = input('trademark'); //商标
        $start_time = input("start_time"); //开始时间
        $end_time = input("end_time");      //结束时间
    
        $init_query = OrderSaleList::where('deleted', 0);
    
        if($name){
            $init_query = $init_query->where("name", "like", "%$name%");
        }
    
        if($trademark){
            $init_query = $init_query->where("trademark", "like", "%$trademark%");
        }
    
        if ($start_time){
            $init_query = $init_query->where("created_at", ">=", $start_time);
        }
        if ($end_time){
            $init_query = $init_query->where("created_at", "<=", $end_time);
        }
        $count = $init_query->count();
        $init_query = $init_query->page($page,$limit)->select();
    
        returnListData($count, "成功", $init_query->toArray(), 0);
    }
}
