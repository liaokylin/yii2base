<?php
namespace app\extensions;


class Extension extends \Twig_Extension
{
    public function getFunctions()
    {
        $options = [
            'is_safe' => ['html'],
        ];
        $functions = [
            new \Twig_SimpleFunction('domain', [$this, 'domain'], $options),
            new \Twig_SimpleFunction('widget', [$this, 'widget'], $options),
        ];
        return $functions;
    }




    public function domain()
    {
        return DOMAIN;
    }

    public function widget($widget, $config = [])
    {
        return $this->resolveAndCall($widget, 'widget', [
            $config,
        ]);
    }

    public function resolveAndCall($className, $method, $arguments = null)
    {
        return $this->call($className, $method, $arguments);
    }

    public function call($className, $method, $arguments = null)
    {
        $callable = [$className, $method];
        if ($arguments === null) {
            return call_user_func($callable);
        } else {
            return call_user_func_array($callable, $arguments);
        }
    }



    public function getName()
    {
        return 'izhupu-extension';
    }
}