<?php
// 应用公共文件

// 成功信息返回
use think\facade\Log;

function successRep($msg="成功", $data=[], $code=200){
    exit(json_encode(["code" => $code, "msg" =>$msg, "data" => $data]));
}

function errorRep($msg="失败", $code=0, $data=[]){
    exit(json_encode(["code" => $code, "msg" =>$msg, "data" => $data]));
}

// 组装放回列表数据
function returnListData($count=0, $msg="", $data=[], $code=200){
    exit(json_encode(["code" => $code, "msg" => $msg, "count" => $count, "data" => $data]));
}

function stockInLeftSideRedis($id,$data){
    $redis = new Redis();
    try {
        $redis->lpush("out_of_stock_product--".$id, $data);
    } catch (RedisException $e) {
        Log::error("redis队列缓存错误：" . $e->getMessage());
    }
}


