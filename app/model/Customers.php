<?php

namespace app\model;

use think\facade\Log;
use think\Model;

class Customers extends Model
{
    // public function getSexAttr($value)
    // {
    //     $sex = [1=>'男',2=>'女'];
    //     return $sex[$value];
    // }
    
    
    // 名称
    public function scopeWhereName($query, $value)
    {
        if ($value) {
           return $query->where('name', 'like', '%' . $value . '%');
        }
        return $query;
    }
    
    // 手机号
    public function scopeWherePhone($query, $value)
    {
        if ($value) {
            return $query->where('phone', 'like', "%$value%");
        }
        return $query;
    }
}