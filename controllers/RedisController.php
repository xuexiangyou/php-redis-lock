<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/22
 * Time: 上午11:00
 */
namespace app\controllers;

use yii\web\Controller;

class RedisController extends Controller
{
    public function actionIndex()
    {
        $redis = \Yii::$app->redis;
    }
}