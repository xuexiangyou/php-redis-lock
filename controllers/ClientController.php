<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/24
 * Time: 下午3:48
 */
namespace app\controllers;


use app\components\YarApi;

use yii\web\Controller;

class ClientController extends Controller
{

    public function actionIndex()
    {
        $condition = ['class'=>'\app\models\User'];
        $yar =  new YarApi();
        $model = $yar->api($condition);
        $query = $model->getAll();
        echo "<pre>";
        foreach ($query as $item) {
            print_r($item->toArray());
        }
        exit;
    }
}