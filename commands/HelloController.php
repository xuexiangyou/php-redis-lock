<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
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
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        return ExitCode::OK;
    }

    public function actionCreateFile()
    {
        $fp = fopen('test.csv','a');
        $time = time();
        // 处理内容
        $content = '';
        for ($i = 1; $i <= 1000000; $i++) {
            $content .= ($time + $i) . rand(0, 9) . PHP_EOL;
        }
        // 写入并关闭资源
        fwrite($fp, $content);
        fclose($fp);
        exit;
    }

    public function actionReadFile()
    {
        ini_set('memory_limit', '-1');
        $startTime = microtime(true);

        $field = ['mobile'];

        $result = $this->readCsv();
        $result = array_unique($result);

        $num = 10;

        $pageSize = ceil(count($result) / $num);
        $ids = [];

        for ($i = 0; $i < $num; $i++) {
            $value = array_slice($result, $i * $pageSize, $pageSize);
            //echo count($value) . "\n";
            $ids[] = $pid = pcntl_fork();
            if ($pid == 0) {
                $command = Yii::$app->db->createCommand();
                $childPageSize = 100;
                $childPage = ceil(count($value) / $childPageSize);
                for ($j = 0; $j < $childPage; $j++) {
                    $data = [];
                    $childValue = array_slice($value, $j * $childPageSize, $childPageSize);
                    foreach ($childValue as $item) {
                        $data[] = [
                            'mobile' => trim($item)
                        ];
                    }
                    $command->batchInsert("cc_phone", $field, $data)->execute();
                }
                exit;
            }
        }
        foreach ($ids as $k => $pid) {
            if ($pid) {
                pcntl_waitpid($pid, $status);
            }
        }
        $endTime = microtime(true);
        $runTime = ($endTime-$startTime)*1000 . ' ms';
        echo "运行时间:" . $runTime;
        exit;
    }

    private function readCsv()
    {
        $result = [];
        $handle = fopen("test.csv", 'rb');
        while (feof($handle)===false) {
            # code...
            $result[] = fgets($handle);
        }
        fclose($handle);
        return $result;
    }

    private function readYieldCvs()
    {
        $handle = fopen("test.csv", 'rb');
        while (feof($handle)===false) {
            # code...
            yield fgets($handle);
        }
        fclose($handle);
    }
}
