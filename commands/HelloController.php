<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\redis\RedisDistributedLock;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $n = 500;
        $redis = \Yii::$app->redis;
        $obj = new RedisDistributedLock($redis, 'test');
        for ($i = 0; $i <= 10; $i++) {
            $unLockIdentify = $obj->acquire();
            var_dump($unLockIdentify);
            $obj->release($unLockIdentify);
            echo $n--;
        }
    }
}
