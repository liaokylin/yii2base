<?php

namespace app\helpers\sphinx;

use yii\base\Component;

include_once __DIR__ . '/sphinxapi.php';

class Sphinx extends Component {

    public $server = '127.0.0.1';

    public $port = 9312;

    public $timeOut = 60;

    public $arrayResult = true;

    private $_client;

    public function init()
    {
        parent::init();
        $this->_client = new SphinxClient();
        $this->_client->setServer($this->server, $this->port);
        $this->_client->SetConnectTimeout($this->timeOut);
        $this->_client->SetArrayResult($this->arrayResult);
    }

    public function __call($name,$parameters)
    {
        $res = null;
        if (method_exists($this->_client, $name)) {
            $res = call_user_func_array(array($this->_client, $name), $parameters);
        } else {
            $res = parent::__call($name, $parameters);
        }
        return $res;
    }
}