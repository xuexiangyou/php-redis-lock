<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/22
 * Time: 下午1:42
 */
namespace app\components\redis;

use Faker\Provider\Uuid;
use yii\base\Exception;

class RedisDistributedLock implements DistributedLock
{
    const LOCK_SUCCESS = 'OK';
    const RELEASE_SUCCESS = 1;
    const SET_IF_NOT_EXIST = 'NX';
    const SET_WITH_EXPIRE_TIME = 'PX';

    /**
     * redis 客户端
     */
    private $redis;

    /**
     * 分布式锁的键值
    */
    private $lockKey;

    /**
     * 锁的超时时间 10s
    */
    private $expireTime = 10000;

    /**
     * 锁等待，防止线程饥饿
     */
    private $acquireTimeout  = 1000;


    /**
     * 获取指定键值的锁
     * @param redis redis Redis客户端
     * @param lockKey 锁的键值
     */
    public function __construct($redis, $lockKey) {
        $this->redis = $redis;
        $this->lockKey = $lockKey;
    }

    /**
     * 获取锁
     **/
    public function acquire()
    {
        try {
            //获取锁的超时时间，超过这个时间则放弃获取锁
            $end = $this->getMillisecond() + $this->acquireTimeout;
            $requireToken = Uuid::uuid();
            while (time() < $end) {
                $result = $this->redis->set($this->lockKey, $requireToken,
                    self::SET_WITH_EXPIRE_TIME, $this->expireTime, self::SET_IF_NOT_EXIST);
                if ($result) {
                    return $requireToken;
                }
                $this->msleep(100);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    /**
     * 释放锁
    */
    public function release($identify)
    {
        if ($identify == null) {
            return false;
        }
        try {
            $script = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('del', KEYS[1]) else return 0 end";
            $result = $this->redis->eval($script, 1, $this->lockKey, $identify);
            if ($result) {
                echo "release lock success, requestToken:{$identify}\n";
                return true;
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return false;
    }

    /**
     * 程序休息毫秒
     * */
    private function msleep($time)
    {
        sleep($time * 1000);
    }

    /**
     * 获取毫秒时间戳
    */
    private function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}