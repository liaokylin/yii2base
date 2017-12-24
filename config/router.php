<?php


$domain = parse_domain($_SERVER['HTTP_HOST']);
define("DOMAIN",$domain);
$prefix = parseHostPrefix($_SERVER['HTTP_HOST']);
define("hostPrefix",$prefix);
$routes = [
    [
        'class' => 'app\components\GroupUrlRule',
        'hostPrefix' => 'm',
        'routePrefix' => 'mobile',
        'rules' => [
            'https://m.' . DOMAIN . '/<controller:\w+>/<action:\w+>' => '/mobile/<controller>/<action>',
        ]
    ],
];
return $routes;

function parseHostPrefix($host){
    $info = explode('.',$host);
    return $info[0];
}

function parse_domain($url){
    $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
    $array_domain = explode(".", $url);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn'){
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)){
            $domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
        else{
            $domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
    }
    else{
        $domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
    }
    return $domain;
}

function isMobile(){
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])){
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])){
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])){
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
            return true;
        }
    }
    return false;
}