<?php
/**
 * Created by PhpStorm.
 * User: lilonggen
 * Date: 2019/1/24
 * Time: 下午3:49
 */
namespace app\controllers;

use Yii;

use yii\base\Exception;
use yii\web\Controller;

class ServerController extends Controller
{
    /**
     * 关闭csft
     * @var string
     * @access public
     */
    public $enableCsrfValidation = false;
    /**
     * ip
     * @var string
     * @access private
     */
    private $ipArr = ['127.0.0.1','192.168.33.11'];
    /**
     * 密码
     * @var string
     * @access private
     */
    private $password = 'Add25f37';
    /**
     * 有效时间   秒
     * @var string
     * @access private
     */
    private $activeTime = 1440;

    public function actionIndex()
    {
        //$service = new \Yar_Server(new Api());
        //$service->handle();

        $request = Yii::$app->request;
        $rpcToken = $request->get('rpctoken');
        $data = $this->rpcDecode($rpcToken);
        if (!$this->auth($data)) {
            return;
        }
        try {
            $server = new \Yar_Server(new $data['class']);
            $server->handle();
        } catch (Exception $e) {
            Yii::error('异常catch');
            return;
        }
    }

    /**
     * 权限认证
     *
     * @author Zhiqiang Guo
     * @return void
     * @throws Exception
     * @access private
     */
    private function auth($param)
    {
        if (!$param) {
            return false;
        }
        //验证IP
        if (!in_array(Yii::$app->request->userIP, $this->ipArr)) {
            return false;
        }

        //有效时间
        if ((time() - $param['time']) > $this->activeTime) {
            return false;
        }
        //验证密码
        if ($param['password'] !== $this->password) {
            return false;
        }
        if(empty($param['class'])){
            return false;
        }
        return true;
    }

    /**
     * 解密
     *
     * @author Zhiqiang Guo
     * @return void
     * @throws Exception
     * @access private
     */
    private function rpcDecode($str)
    {
        if ($str) {
            return json_decode(base64_decode($str),true);
        }
        return [];
    }
}