<?php


namespace app\components;

use \Yii;
use yii\log\Logger;
use yii\log\FileTarget;
use yii\base\InvalidConfigException;


class DetailFileTarget extends FileTarget
{
    public $url;

    public function export()
    {
        //日志格式化开始
        $stack = [];
        $sql_profile = '';
        $php_profile = '';
        $sql_total = 0;
        $sql_total_time = 0.0;

        if (!$this->url)
        {
            return;
        }

        $absoluteUrl = \Yii::$app->request->absoluteUrl;
        if (strpos($absoluteUrl, $this->url) === false)
        {
            return;
        }

        foreach ($this->messages as $message)
        {
            if ($message[1] == Logger::LEVEL_PROFILE_BEGIN)
            {
                array_push($stack, $message);
            }
            if ($message[1] == Logger::LEVEL_PROFILE_END)
            {
                $endtime = $message[3];
                $msg = array_pop($stack);
                $begintime = $msg[3];
                $elapsedtime = number_format($endtime - $begintime, 3) * 1000;
                if ($message[2] == 'yii\db\Command::query')
                {
                    $sql_total += 1;
                    $sql_total_time += floatval($elapsedtime);
                    $sql_profile .=  $message[0] . " ({$elapsedtime}ms)\n";
                }
                else if ($message[2] == 'app\web\index')
                {
                    $php_profile = $message[0] . " total time: {$elapsedtime}ms sql time: {$sql_total_time}ms, sql count: {$sql_total}\n";
                }
            }
        }

        $text = date('Y-m-d H:i:s', microtime(true)) . " " . $php_profile . $sql_profile . "\n";
        //日志格式化结束 junping

        if (($fp = @fopen($this->logFile, 'a')) === false) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFile}");
        }
        @flock($fp, LOCK_EX);
        @fwrite($fp, $text);
        @flock($fp, LOCK_UN);
        @fclose($fp);
    }
}