<?php
/**
 * Created by PhpStorm.
 * User: junping
 * Date: 2015/5/6
 * Time: 16:55
 */

return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'logFile' => '@runtime/logs/error.log',
            'logVars' => [],
            'prefix' => function() {
                $request = Yii::$app->getRequest();
                $ip = $request instanceof yii\web\Request ? $request->getUserIP() : '-';
                $url = $request instanceof yii\web\Request ? $request->absoluteUrl : '-';
                return "[$ip][$url]";
            },
            'enabled' => true,
        ],
        [
            'class' => 'app\components\ProfileFileTarget',
            'levels' => ['profile'],
            'logFile' => '@runtime/logs/profile.log',
            'categories' => ['app\web\index', 'yii\db\Command::query', 'app\function'],
            'enabled' => true,
        ],
    ],
];