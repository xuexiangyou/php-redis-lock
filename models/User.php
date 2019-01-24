<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2018/12/28
 * Time: 下午6:21
 */
namespace app\models;

use app\components\Util;
use yii\data\Pagination;
use app\services\SigninService;

class User extends BaseActiveRecord
{
    /**
     * 用户属性定义
     */
    const PROP_NORMAL_USER = 1; // 正式用户（非新用户）

    /**
     * 用户生成海报获取积分属性
    */
    const PROP_CREATE_POSTER_GET_BONUS = 2;

    public static function tableName()
    {
        return '{{cc_welfare_user}}';
    }

    public function getAll()
    {
        return static::find()->all();
    }
}