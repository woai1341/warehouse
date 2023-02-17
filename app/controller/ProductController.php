<?php

namespace app\controller;

use app\BaseController;
use app\model\Product;

class ProductController extends  BaseController
{
    public function index(){
        return view("./product/index");
    }

    public function getListData(){
        $page = input("page", 1);
        $limit = input("limit", 10);
        $name = input('name');          //产品名称
        $trademark = input('trademark'); //商标


        $init_query = Product::where('deleted', 0);

        if($name){
            $init_query = $init_query->where("name", "like", "%$name%");
        }

        if($trademark){
            $init_query = $init_query->where("trademark", "like", "%$trademark%");
        }        
        
        $init_query = $init_query->page($page,$limit)->select();
        
        returnListData($init_query->count(), "成功", $init_query->toArray(), 0);
    } 

    public function add()
    {
        if ($this->request->isPost()) {
            $data = input();
            if (empty($data)) errorRep("不能添加空数据", 0, []);
            $insertData = [
                'name' => $data['name'],
                'trademark' => $data['trademark'],
                'foot_distance' => $data['foot_distance'],
                'size' => $data['size'],
                'count' => $data['count'],
                'unit' => $data['unit'],
                'is_high' => $data['is_high'],
                'is_braid' => $data['is_braid'],
            ];
            try {
                Product::insert($insertData);
            } catch (Exception $e) {
                errorRep("添加失败" . $e->getMessage(), 0, []);
            }
            successRep("添加成功", []);
        } else {
            return \view("./product/add");
        }
        return false;
    }
    
}