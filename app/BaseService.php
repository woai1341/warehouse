<?php

namespace app;

class BaseService
{


    //私有静态变量
    protected static $instance;


    /**
     * 单例模式
     *static 代表调用类
     *self 代表当前类
     * @return static
     */
    public static function getInstance(){

        if (!static::$instance instanceof static){
            static::$instance=new static();
        }

        return static::$instance;
    }

    //私有克隆函数
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    //私有构造方法
    private function __construct()
    {
    }
}