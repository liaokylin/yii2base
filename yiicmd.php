<?php

/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
setcookie('XDEBUG_SESSION',1,time()+8640000);
// fcgi doesn't have STDIN and STDOUT defined by default
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');



$config = require(__DIR__ . '/config/console.php');

$_SERVER['argv'][]='spider/mei-shi-jie-list';
$_SERVER['argc']=count($_SERVER['argv']);
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
