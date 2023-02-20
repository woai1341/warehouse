<?php

namespace app\controller;

use app\BaseController;
use app\model\CustomersSaleList;

class SaleController extends BaseController
{
    //列表页面
    public function index()
    {
        return view("./sale/index");
    }
    
    // 列表数据
    public function getListData()
    {
        $page = input("page", 1);
        $limit = input("limit", 10);
        $customer_name = input('customer_name');          //客户名称
        $product_name = input('product_name');            //客户名称
        $trademark = input('trademark');                  //商标
        $start_time = input("start_time");                //开始时间
        $end_time = input("end_time");                    //结束时间
        
        $init_query = CustomersSaleList::where('deleted', 0);
        
        if ($customer_name) {
            $init_query = $init_query->where("customer_name", "like", "%$customer_name%");
        }
        
        if ($product_name) {
            $init_query = $init_query->where("product_name", "like", "%$product_name%");
        }
        
        if ($trademark) {
            $init_query = $init_query->where("trademark", "like", "%$trademark%");
        }
        
        if ($start_time) {
            $init_query = $init_query->where("created_at", ">=", $start_time);
        }
        
        if ($end_time) {
            $init_query = $init_query->where("created_at", "<=", $end_time);
        }
        
        $init_query = $init_query->with([
            'hasOneProduct' => function ($query) {
                $query->hidden(['deleted', 'created_at', 'updated_at'])->select();
            },
            'hasOneCustomers' => function ($query) {
                $query->hidden(['deleted', 'created_at', 'updated_at'])->select();
            },
        ])
            ->order("id", "DESC")
            ->page($page, $limit)
            ->select();
        
        returnListData($init_query->count(), "成功", $init_query->toArray(), 0);
    }
    
    public function add_html()
    {
        return view("./sale/add");
    }
    
    function explode(){
        $data = input("data");
        return $data;
    }
}