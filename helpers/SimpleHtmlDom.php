<?php
/**
 * Created by PhpStorm.
 * User: 廖麒麟
 * Date: 2017/6/10
 * Time: 11:20
 */

namespace app\helpers;


class SimpleHtmlDom extends simple_html_dom
{

    public $statusCode;
    protected $_ch;
	public function getPage($url,$gzip = false)
    {
        preg_match('@(?<=://)[a-zA-Z\.0-9]+(?=\/)@',$url,$match);
        $this->host = $match[0];
        $ch = curl_init();
        $this->_ch = $ch;
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = $this->initHeader();
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
        $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $result;
    }


    public function getHttpStatusCode(){
        $statusCode = curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
        return $statusCode;
    }
	
	public function initHeader()
    {
        $eqid= md5(time());
        $Referer = "http://www.baidu.com/link?url=aXOk47huQZK39UxfNwWMLXPHTATusdrhAFcHopcoRSDS0BaqUDO87OYLN0IiBenS&wd=&eqid=".$eqid;
        $ip = rand(201, 255).".".rand(181, 255).".".rand(1, 255).".".rand(1, 255);
        $header = array (
            "POST HTTP/1.1",
            "Host: ".$this->host,
            "Content-Type: text/xml; charset=utf-8",
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            "Referer: $this->host",
            "X-FORWARDED-FOR:" .$ip,
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
            "Connection: keep-alive"
        );
        return $header;
    }
}