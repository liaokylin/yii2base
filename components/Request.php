<?php

namespace app\components;


class Request extends \yii\web\Request
{

    public function getBodyParam($name, $defaultValue = null)
    {
        $params = $this->getBodyParams();
        return !empty($params[$name]) ? $params[$name] : $defaultValue;
    }
}