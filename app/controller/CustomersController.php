<?php

namespace app\controller;

use app\BaseController;
use app\model\Customers;
use think\db\Fetch;
use think\Exception;
use think\facade\Request;
use think\facade\View;

class CustomersController extends BaseController
{
    // 客户列表
    public function index()
    {
        return view("./customers/index");
    }
    
    // 编辑页面
    public function editHtml($id)
    {
        $customers = Customers::where(["id" => $id, "deleted" => 1])->find();
        // 没传客户id直接返回错误页面
        if (empty($customers) || empty($id)) {
            return view("./error/error");
        }
        View::assign('id', $customers["id"]);
        View::assign('username', $customers["name"]);
        View::assign('email', $customers["email"]);
        View::assign('phone', $customers["phone"]);
        View::assign('sex', $customers["sex"]);
        
        return view("./customers/edit");
    }
    
    public function getCustomersInfo()
    {
        $page = input("page", 1);
        $limit = input("limit", 10);
        $name = input('username');
        $phone = input('phone');
        
        $where = [
            'deleted' => 1,
        ];
        
        $init_query = Customers::where($where);
        
        if (!empty($name)) {
            $init_query = $init_query->where('name', 'like', '%' . $name . '%');
        }
        
        if (!empty($phone)) {
            $init_query = $init_query->where('phone', 'like', '%' . $phone . '%');
        }
        
        $init_query = $init_query
            ->page($page, $limit)
            ->select();
        
        if ($init_query->isEmpty()) {
            returnListData(0, "成功", [], 0);
        }
        $count = $init_query->count();
        $data = $init_query->toArray();
        returnListData($count, "成功", $init_query, 0);
    }
    
    
    public function update($id)
    {
        
        $data = input("data");
        if (empty($data)) errorRep("请输入数据", 101, []);
        try {
            Customers::where(["id" => $id, "deleted" => 1])->update(['name' => $data['username'], 'sex' => $data['sex'], 'phone' => $data['phone'], 'email' => $data['email']]);
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
            Customers::where("id", $id)->where("deleted", 1)->update(["deleted" => 0]);
        } catch (Exception $e) {
            errorRep("删除失败", 0, []);
        }
        successRep("删除成功", [], 200);
    }
    
    public function add()
    {
        if ($this->request->isPost()) {
            $data = input();
            if (empty($data)) errorRep("不能添加空数据", 0, []);
            $insertData = [
                'name' => $data['username'],
                'sex' => $data['sex'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
            ];
            try {
                Customers::insert($insertData);
            } catch (Exception $e) {
                errorRep("添加失败" . $e->getMessage(), 0, []);
            }
            successRep("添加成功", []);
        } else {
            return \view("./customers/add");
        }
        return false;
    }
    
}