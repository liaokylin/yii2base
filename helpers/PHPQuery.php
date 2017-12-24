<?php
/**
 * Created by PhpStorm.
 * User: 廖麒麟
 * Date: 2016/11/26
 * Time: 8:59
 */
namespace app\helpers;


require_once \Yii::getAlias('@app').DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'phpQuery'.DIRECTORY_SEPARATOR.'phpQuery.php';



class PHPQuery extends \phpQuery
{



    public static $host;

    protected static $_ch;

    public static function getPage($url,$gzip = false)
    {
        preg_match('@(?<=://)[a-zA-Z\.0-9]+(?=\/)@',$url,$match);
        self::$host = $match[0];
        $ch = curl_init();
        self::$_ch = $ch;
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = self::initHeader();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  //设置头信息的地方
        curl_setopt($ch, CURLOPT_HEADER, 0);    //不取得返回头信息
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($gzip)
        {
            curl_setopt($ch, CURLOPT_ENCODING, "gzip");// 关键在这里
        }
        $result = curl_exec($ch);
        return $result;
    }

    public static function initHeader()
    {
        $ip = rand(201, 255).".".rand(181, 255).".".rand(1, 255).".".rand(1, 255);
        $header = array (
            "POST HTTP/1.1",
            "Host: ".self::$host,
            "Content-Type: text/xml; charset=utf-8",
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            "Referer: ".self::$host,
            "X-FORWARDED-FOR:" .$ip,
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
            "Connection: keep-alive"
        );
        return $header;
    }

    public static function getHttpStatusCode(){
        $statusCode = curl_getinfo(self::$_ch, CURLINFO_HTTP_CODE);
        return $statusCode;
    }

}