<?php


namespace app\commands;
use app\helpers\Curl;
use app\helpers\Logger;
use app\helpers\SimpleHtmlDom;



class Controller extends \yii\console\Controller
{

    public $curl;
    public $html;



    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Logger::instance()->init(\Yii::getAlias('@runtime') . "/logs/console/{$this->id}_{$action->id}.log");
            return true;
        }
        else{
            return false;
        }
    }

    protected function log($message)
    {
        $backtrace = debug_backtrace();
        $file = $backtrace[0]['file'];
        $arr = explode(DIRECTORY_SEPARATOR, $file);
        $file = array_pop($arr);
        $line = $backtrace[0]['line'];
        $func = $backtrace[1]['function'];
        Logger::instance()->info($message, $file, $line, $func);
    }

    public function init()
    {
        $this->curl = new Curl();
        $this->html = new SimpleHtmlDom();
        parent::init();
        ini_set('memory_limit', '1024M');
    }


    public function encode($str)
    {
        $out = '';
        $len = mb_strlen($str, 'utf-8');
        for ($i = 0; $i < $len; $i++){
            $tmp = mb_substr($str, $i, 1, 'utf-8');
            $out .= bin2hex(@iconv('utf-8', 'gb2312', $tmp));
        }
        return $out;
    }

    public function decode($str)
    {
        $out = '';
        $str = hex2bin($str);
        $len = mb_strlen($str, 'gb2312');
        for ($i = 0; $i < $len; $i++){
            $tmp = mb_substr($str, $i, 1, 'gb2312');
            $out .= @iconv('gb2312', 'utf-8//ignore', $tmp);
        }
        return $out;
    }

    protected function string($str)
    {
        $preg = "/[a-zA-Z\x{4e00}-\x{9fa5}]+/u";
        if(preg_match_all($preg, $str, $matches))
        {
            return implode(' ', $matches[0]);
        }
        return '';
    }

    public  function md5filepath($md5str)
    {
        return trim(preg_replace('@\w{2}@', "$0/", substr($md5str, 0, 6)), '/') . '/' . $md5str . ".jpg";
    }

}