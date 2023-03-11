<?php

namespace app\controller;

use app\BaseController;
use think\Exception;
use think\facade\View;

class TrademarkController extends BaseController
{
    public function index(){
        return view("./product/index");
    }
    
    public function getListData(){
        $page = input("page", 1);
        $limit = input("limit", 10);
        $name = input('name');          //产品名称
        $trademark = input('trademark'); //商标
        $start_time = input("start_time"); //开始时间
        $end_time = input("end_time");      //结束时间
        
        $init_query = Product::where('deleted', 0);
        
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
                'is_chip' => $data['is_chip'],
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
    
    
    // 编辑页面
    public function editHtml($id)
    {
        $product = Product::where(["id" => $id, "deleted" => 0])->find();
        // 没传客户id直接返回错误页面
        if (empty($product) || empty($id)) {
            return view("./error/error");
        }
        View::assign('id', $product["id"]);
        View::assign('name', $product["name"]);
        View::assign('trademark', $product["trademark"]);
        View::assign('size', $product["size"]);
        View::assign('count', $product["count"]);
        View::assign('unit', $product["unit"]);
        View::assign('is_high', $product["is_high"]);
        View::assign('is_braid', $product["is_braid"]);
        View::assign('is_chip', $product["is_chip"]);
        View::assign('foot_distance', $product["foot_distance"]);
        
        return view("./product/edit");
    }
    
    
    public function update($id)
    {
        $data = input();
        if (empty($data)) errorRep("请输入数据", 101, []);
        try {
            Product::where(["id" => $id, "deleted" => 0])->update(
                ['name' => $data['name'],
                    'trademark' => $data['trademark'],
                    'size' => $data['size'],
                    'count' => $data['count'],
                    'unit' => $data['unit'],
                    'is_high' => $data['is_high'],
                    'is_braid' => $data['is_braid'],
                    'is_chip' => $data['is_chip'],
                    'foot_distance' => $data['foot_distance']
                ]);
        } catch (\Exception $e) {
            errorRep("修改失败" . $e->getMessage(), 0, []);
        }
        successRep("修改成功", []);
    }
    
    // 删除
    public function del()
    {
        $id = input("id");
        
        if (empty($id)) errorRep("失败", 101, []);
        
        try {
            Product::where("id", $id)->where("deleted", 0)->update(["deleted" => 1]);
        } catch (Exception $e) {
            errorRep("删除失败", 0, []);
        }
        successRep("删除成功", [], 200);
    }
}