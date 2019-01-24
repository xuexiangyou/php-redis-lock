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
        $arr = [38, 5, 97, 76, 13, 27, 49];
        $insetArr = $this->insertSort($arr);
        $selectArr = $this->selectSort($arr);
        echo "nihao";
        exit;
    }

    /**
     * 插入排序
    */
    public function insertSort($arr)
    {
        $count = count($arr);
        for($i = 1; $i < $count; $i++){
            $tmp = $arr[$i];
            $j = $i - 1;
            while($arr[$j] > $tmp) {
                $arr[$j+1] = $arr[$j];
                $arr[$j] = $tmp;
                $j--;
            }
        }
        return $arr;
    }

    /**
     * 选择排序
    */
    public function selectSort($arr)
    {
        $count = count($arr);
        for($i=0; $i<$count; $i++){
            $k = $i;
            for($j=$i+1; $j<$count; $j++){
                if ($arr[$k] > $arr[$j]) $k = $j;
            }
            if($k != $i){
                $tmp = $arr[$i];
                $arr[$i] = $arr[$k];
                $arr[$k] = $tmp;
            }
        }
        return $arr;
    }
}