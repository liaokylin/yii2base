<?php
/**
 * Created by PhpStorm.
 * User: 廖麒麟
 * Date: 2017/7/31
 * Time: 21:11
 */

namespace app\helpers;


use yii\helpers\Json;

class WeiXinHelper
{

    protected static $AppSecret = "22ba27cd36896e594167767960cf039c";
    protected static $AppID = "wx813008760b526cab";

    public function getSignPackage() {
        $jsapiTicket = $this->getTicket();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "debug" => true,
            "appId"     => self::$AppID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    public function functionList(){
        $list = [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'scanQRCode'
        ];
        return $list;
    }
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getTicket(){
        $cache = \Yii::$app->cache;
        $ticket = $cache->get('ticket');
        if($ticket){
           return $ticket;
        }
        $fileCache = \Yii::$app->filecache;
        $ticket = $fileCache->get('ticket');
        if($ticket){
            return $ticket;
        }
        $token = $this->getAccessToken();
        $curl = new Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
        $curl->setOption(CURLOPT_SSL_VERIFYHOST,false);
        $json = $curl->get("https://api.weixin.qq.com/cgi-bin/ticket/getticket",['access_token'=>$token,'type'=>'jsapi']);
        if($json){
            $ticket = Json::decode($json);
            if(!empty($ticket['ticket'])){
                $cache->set('ticket',$ticket['ticket'],7200);
                $fileCache->set('ticket',$ticket['ticket'],7200);
                return $ticket['ticket'];
            }
        }
        return '';
    }

    public function getAccessToken(){
        $cache = \Yii::$app->cache;
        $access_token = $cache->get('access_token');
        if($access_token){
            return $access_token;
        }
        $fileCache = \Yii::$app->filecache;
        $access_token = $fileCache->get('access_token');
        if($access_token){
            return $access_token;
        }
        $curl = new Curl();
        $param = [
           'appid' => self::$AppID,
            'secret' =>self::$AppSecret
        ];
        $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
        $curl->setOption(CURLOPT_SSL_VERIFYHOST,false);
        $json = $curl->get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential",$param);
        $token = Json::decode($json);
        if(!empty($token['access_token'])){
            $cache->set('access_token',$token['access_token'],7200);
            $fileCache->set('access_token',$token['access_token'],7200);
            return $token['access_token'];
        }
        return '';
    }
}