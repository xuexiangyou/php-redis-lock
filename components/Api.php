<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/24
 * Time: 下午5:19
 */
namespace app\components;

class Api
{
    /**
     * the doc info will be generated automatically into service info page.
     * @params
     * @return
     */
    public function some_method($parameter, $option = "foo") {
        return [1,2,3];
    }

    protected function client_can_not_see() {
    }
}