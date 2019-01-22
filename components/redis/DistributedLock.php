<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/22
 * Time: 下午1:37
 */
namespace app\components\redis;

interface DistributedLock
{

    /**
     * 获取锁
    */
    public function acquire();


    /**
     * 释放锁
    */
    public function release($identify);

}