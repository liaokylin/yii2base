<?php


namespace app\components;


class GroupUrlRule extends \yii\web\CompositeUrlRule
{
    public $name;

    /**
     * @var array the rules contained within this composite rule. Please refer to [[UrlManager::rules]]
     * for the format of this property.
     * @see prefix
     * @see routePrefix
     */
    public $rules = [];
    /**
     * @var string the prefix for the pattern part of every rule declared in [[rules]].
     * The prefix and the pattern will be separated with a slash.
     */

    public $hostPrefix;

    public $hostPattern;

    public $pathPrefix;

    public $pathPattern;

    /**
     * @var string the prefix for the route part of every rule declared in [[rules]].
     * The prefix and the route will be separated with a slash.
     * If this property is not set, it will take the value of [[prefix]].
     */
    public $routePrefix;

    public $routePattern;

    /**
     * @var array the default configuration of URL rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = ['class' => 'app\components\UrlRule'];

    protected function createRules()
    {
        $rules = [];
        foreach ($this->rules as $key => $rule) {
            if (!is_array($rule)) {
                $rule = [
                    'pattern' => ltrim($key),
                    'route' => ltrim($rule),
                ];
            }
            $rule = \Yii::createObject(array_merge($this->ruleConfig, $rule));
            if (!$rule instanceof \yii\web\UrlRuleInterface) {
                throw new \yii\base\InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $rules[] = $rule;
        }
        return $rules;
    }

    public function parseRequest($manager, $request)
    {
        $hostInfo = $request->hostInfo;
        $pathInfo = $request->pathInfo;

        if ($this->hostPrefix) {
            if ($hostInfo == 'http://' . $this->hostPrefix . '.' . DOMAIN) {
                return parent::parseRequest($manager, $request);
            }
            elseif($hostInfo == 'https://' . $this->hostPrefix . '.' . DOMAIN)
            {
                return parent::parseRequest($manager, $request);
            }
            else {
                return false;
            }
        }

        if ($this->hostPattern) {
            if (preg_match($this->hostPattern, $hostInfo)) {
                return parent::parseRequest($manager, $request);
            } else {
                return false;
            }
        }

        if ($this->pathPrefix) {
            $paths = explode('/', trim($pathInfo, '/'));
            if (is_array($paths) && $paths[0] == $this->pathPrefix) {
                return parent::parseRequest($manager, $request);
            } else {
                return false;
            }
        }

        if ($this->pathPattern) {
            $paths = explode('/', trim($pathInfo, '/'));
            if (is_array($paths) && preg_match($this->pathPattern, $paths[0])) {
                return parent::parseRequest($manager, $request);
            } else {
                return false;
            }
        }
        return false;
    }

    public function createUrl($manager, $route, $params)
    {
        if ($this->routePrefix) {
            $routes = explode('/', trim($route, '/'));
            if (is_array($routes) && $routes[0] == $this->routePrefix) {
                return parent::createUrl($manager, $route, $params);
            } else {
                return false;
            }
        }

        if ($this->routePattern) {
            $routes = explode('/', trim($route, '/'));
            if (is_array($routes) && preg_match($this->routePattern, $routes[0])) {
                return parent::createUrl($manager, $route, $params);
            } else {
                return false;
            }
        }
        return false;
    }

}
