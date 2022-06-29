<?php


namespace core\base\controller;


use core\base\exceptions\RouteException;

abstract class BaseController
{
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    /**
     * @throws RouteException
     */
    public function route()
    {
        try {
            $controller = str_replace('/', '\\', $this->controller);
            $object = new \ReflectionMethod($controller, 'request');
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outMethod' => $this->outputMethod
            ];
            $object->invoke(new $controller, $args);
        }
    catch (\ReflectionException $e)
    {
        throw new RouteException($e);
    }
    }

}