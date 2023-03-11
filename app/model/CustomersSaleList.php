<?php

namespace app\model;

use think\Model;
use think\model\relation\HasOne;

class CustomersSaleList extends Model
{
    protected $table = "customers_sale_list";
    
    // 关联单个产品信息
    public function hasOneProduct(): HasOne
    {
        return $this->hasOne(Product::class, "id", 'product_id');
    }
    // 关联客户信息
    public function hasOneCustomers(): HasOne
    {
        return $this->hasOne(Customers::class, "id", 'customer_id');
    }
}