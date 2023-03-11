<?php

namespace app\controller;

use app\BaseController;
use app\model\Product;


class CommController extends BaseController
{
    function getAllProductData(){
        $name = Product::where("deleted",0)->field(["name"])->select();
        if (!$name->isEmpty()){
            $arr = [];
            foreach ($name->toArray() as $k => $v){
                $arr[$k]["name"] = $k+1;
                $arr[$k]["value"] = $v['name'];
            }
            return json($arr);
        }
        return [];
    }
}