<?php

namespace app\service;

use app\BaseService;
use app\model\OutOfStock;
use app\model\Product;
use think\Exception;
use think\facade\Db;
use think\facade\Log;

class ProductService extends  BaseService
{

    public function toSpecialString($product): string
    {
        $unit = "";
        $is_high = "";
        $is_braid = "";
        $foot_distance = "";

        if($product){
            if ($product->unit == 1){
                $unit = "K";
            }else{
                $unit ="PCS";
            }
            if ($product->is_high == 1){
                $is_high = "高频";
                $foot_distance = $product->foot_distance;
            }
            if ($product->is_braid == 1){
                $is_braid = "贴片电解电容";
            }
        }
//        组装要存进来的 数据
        return "商标: " . $product->trademark . " 品名规格: " . $product->name. " 尺寸: " . $product->size .
            " 数量: " .$product->count . " 单位: " . $unit . " " . $is_high . " 脚距：" . $foot_distance . $is_braid;
    }

//    减少库存

    /**
     * @param $id | 产品id
     * @param $count | 要出库的数量
     * @param $oldCount | 库存数量
     * @param $dataString | 出库产品的组合信息
     * @return boolean
     */
    public function reductionStock($id,$count,$oldCount,$dataString){
//        更新产品表中的库存数量
//        更新新出库表中的剩余量和出库量
        Db::startTrans();
        try {
            if ((float)$count > (float)$oldCount){
                errorRep("数量超过库存");
            }
            //剩余 surplus remainder
            $surplus_count =(float)$oldCount - (float)$count;
//            更新产品信息表数量
            Product::where(["id" => $id, "deleted" => 0])->update(["count" => $surplus_count]);
//            更新出库记录表数据
            OutOfStock::insert([
                "product_id" => $id,
                "product_info" => $dataString,
                "count" => $count,
                "remaining_quantity" => $surplus_count,
                "operate_user_id" => 1 //暂时设定为1，后面加上jwt后设置成用户id
                ]);
            Db::commit();
            return true;
        }catch (Exception $e){
            Log::error("出库操作失败". $e->getMessage());
            echo $e->getMessage();
            Db::rollback();
            return false;
        }
    }
}