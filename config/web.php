<?php
$params = require_once __DIR__.'/params.php';

$config = [
    'id' => 'basic',
    'name' => 'xxx',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout'=>false,
    'components' => [
        'request' => [
            'cookieValidationKey' => 'WkMHI0QBDnPQHTIvm_RGf-zr5NEIO2Ll',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'ruleConfig' => ['class' => 'app\components\UrlRule'],
            'rules'=>require(__DIR__ . '/router.php'),
        ],
        'sphinx' => require(__DIR__ . '/sphinx.php'),
        'view' => [
            'renderers' => [
                'html' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'globals' => ['html' => '\yii\helpers\Html'],
                    // set cachePath to false in order to disable template caching
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'lexerOptions' =>[
                        'tag_comment'  => ['{#', '#}'],
                        'tag_block'    => ['{%', '%}'],
                        'tag_variable' => ['{{', '}}']
                    ],
                    'extensions' => [
                        'app\extensions\Extension',
                        'Twig_Extension_StringLoader',
                    ],
                ],
            ],
        ],
        'filecache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cache' => [ 'class' => 'yii\caching\FileCache',],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'log' => require (__DIR__.'/log.php'),
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
