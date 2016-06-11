<?php

namespace Eschrade\AsyncPack;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

class ReflectionDi implements ContainerInterface
{
    protected $instances = [];

    public function get($id, $params = [])
    {

        if (!$this->has($id)) {
            $reflectionClass = new \ReflectionClass($id);
            if ($reflectionClass->hasMethod('__construct') && $reflectionClass->getConstructor()->getParameters()) {
                $parameters = $reflectionClass->getConstructor()->getParameters();
                $constructorParams = [];
                foreach ($parameters as $parameter) {
                    if (!isset($params[$parameter->getName()]) && !$parameter->isOptional()) {
                        throw new MissingParameterException('Messing the required parameter: ' . $parameter->getName());
                    } else if (isset($params[$parameter->getName()])) {
                        $constructorParams[] = $params[$parameter->getName()];
                    }
                }
                $instance = $reflectionClass->newInstanceArgs($constructorParams);
            } else {
                $instance = $reflectionClass->newInstance();
            }
            $this->instances[$id] = $instance;
        }
        return $this->instances[$id];
    }

    public function has($id)
    {
        return isset($this->instances[$id]);
    }


}
