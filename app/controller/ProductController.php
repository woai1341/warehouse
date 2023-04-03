<?php

namespace app\controller;

use app\BaseController;
use app\model\OutOfStock;
use app\model\Product;
use app\model\ProductPrice;
use app\model\Trademark;
use app\service\ProductService;
use think\Exception;
use think\facade\View;

class ProductController extends  BaseController
{
    public function index(){
        $data = Trademark::where("deleted", 0)->select();
        View::assign("data",$data);
        return view("./product/index");
    }

    public function getListData(){
        $page = input("page", 1);
        $limit = input("limit", 10);
        $name = input('name');          //产品名称
        $trademark = input('trademark'); //商标
        $start_time = input("start_time"); //开始时间
        $end_time = input("end_time");      //结束时间
        $size = input("size"); //尺寸
        $is_chip = input("is_chip"); //是否贴片
        $is_high = input("is_high"); //是否高频
        $is_braid = input("is_braid"); //是否编带
        
        $init_query = Product::where('deleted', 0);
        
        if($name){
            $init_query = $init_query->where("name", "like", "%$name%");
        }

        if($trademark){
            $init_query = $init_query->where("trademark", "like", "%$trademark%");
        }
        if($size){
            $init_query = $init_query->where("size", "like", "%$size%");
        }
        if ($start_time){
            $init_query = $init_query->where("created_at", ">=", $start_time);
        }
        if ($end_time){
            $init_query = $init_query->where("created_at", "<=", $end_time);
        }
        if ($is_chip){
            $init_query = $init_query->where("is_chip", $is_high);
        }
        if ($is_high){
            $init_query = $init_query->where("is_high", $is_high);
        }
        if ($is_braid){
            $init_query = $init_query->where("is_braid", $is_braid);
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
                // "amount" => $data["price"],
                'trademark' => $data['trademark'],
                'foot_distance' => $data['foot_distance'],
                'size' => $data['size'],
                'count' => $data['count'],
                'unit' => $data['unit'],
                'is_high' => $data['is_high'],
                'is_braid' => $data['is_braid'],
                'is_chip' => $data['is_chip'],
                'remark' => $data['remark']
            ];
            try {
                $product_id = Product::insertGetId($insertData);
                // ProductPrice::insert([
                //     "product_id" => $product_id,
                //     "amount" => $data["price"]
                // ]);
            } catch (Exception $e) {
                errorRep("添加失败" . $e->getMessage(), 0, []);
            }
            successRep("添加成功", []);
        } else {
            // 查询拥有的产品
            // View::assign("");
            $data = Trademark::where("deleted", 0)->select();
            View::assign("data",$data);
            
            return \view("./product/add");
        }
        return false;
    }
    //价格波动图
    public function getEchartsLines($id){
        if ($this->request->isGet()){
            View::assign('id', $id);
            return \view("./product/price_change");
        }else{
            // $查询某个产品的价格
        }
       
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
        // View::assign("price", $product["amount"]);
        View::assign('trademark', $product["trademark"]);
        View::assign('size', $product["size"]);
        View::assign('count', $product["count"]);
        View::assign('unit', $product["unit"]);
        View::assign('is_high', $product["is_high"]);
        View::assign('is_braid', $product["is_braid"]);
        View::assign('is_chip', $product["is_chip"]);
        View::assign('foot_distance', $product["foot_distance"]);
        View::assign('remark', $product["remark"]);
        View::assign('amount', $product["amount"]);
        $data = Trademark::where("deleted", 0)->select();
        View::assign("data",$data);
        return view("./product/edit");
    }
    
    
    public function update($id)
    {
        $data = input();
        if (empty($data)) errorRep("请输入数据", 101, []);
        try {
            Product::where(["id" => $id, "deleted" => 0])->update(
                ['name' => $data['name'],
                    // "amount" => $data["price"],
                    'trademark' => $data['trademark'],
                    'size' => $data['size'],
                    'count' => $data['count'],
                    'unit' => $data['unit'],
                    'is_high' => $data['is_high'],
                    'is_braid' => $data['is_braid'],
                    'is_chip' => $data['is_chip'],
                    'foot_distance' => $data['foot_distance'],
                    "updated_at" => date("Y-m-d H:i:s"),
                    'remark' => $data['remark'],
                ]);
            // if (ProductPrice::where(["product_id" => $id, 'deleted' => 0])->value("id")){
            //     ProductPrice::where(["product_id" => $id, 'deleted' => 0])->update(["deleted" => 1]);
            // }
            // ProductPrice::insert( ["product_id" => $id,"amount" => $data["price"]]);
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
//    出库
    function outOfStock($id){
        if (empty($id)) errorRep("参数必填");
//        获取数量
        $count = input("count");
        if (empty($count) || $count == 0 || $count == "0"){
            errorRep("请输入正确且大于0的库存值");
        }


//        id不为空，查询相应id的数据，丢进左侧的redis队列中，查询右侧，削峰更新出库信息
//        1、查询数据
        $product = Product::where(["id" => $id, 'deleted' => 0])->find();
        if (empty($product)){
            errorRep("产品不存在");
        }
//      判断库存是否小于输入要出库的量
        if ((float)$product->count < (float)$count || (float)$product->count == 0){
            errorRep("库存不足");
        }
//        转化成特定的产品规格信息
        $stock_string = ProductService::getInstance()->toSpecialString($product);

//        减少当前库存信息
        $res = ProductService::getInstance()->reductionStock($id,$count,$product->count,$stock_string,$product->unit);
        if ($res){
            successRep("出库成功");
        }
        errorRep("出库失败");

    }
    
    public function outHTML($id){
        View::assign('id', $id);
        return \view("./product/out");
    }
    
    public function showTime($id){
        if (empty($id)) errorRep("参数不能为空");
        
        $data = OutOfStock::where(["product_id" => $id, "deleted" => 0, "status" => 1])
            ->field([
                'product_info','count','remaining_quantity','created_at'
            ])->select();
        
        if ($data->isEmpty()){
            $data[0] =  [
                "product_info" => "暂无记录",
                "count" => 0,
                "remaining_quantity" => Product::where(["id" => $id, "deleted" => 0])->value('count'),
                "created_at" => date("Y-m-d H:i:s") ?? "2023-03-12"
            ];
        }
        $data = $data->toArray();
        
        View::assign("data", $data);
       
        return view("./product/time_line");
        
    }
}
