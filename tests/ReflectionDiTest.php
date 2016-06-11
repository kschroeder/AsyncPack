<?php

namespace Eschrade\AsyncPack\Test;

use Eschrade\AsyncPack\ReflectionDi;

class ReflectionDiTest extends \PHPUnit_Framework_TestCase
{

    public function testGetWithNoParams()
    {
        $di = new ReflectionDi();
        $result = $di->get('Eschrade\AsyncPack\Test\NoParamObject');
        $this->assertInstanceOf('Eschrade\AsyncPack\Test\NoParamObject', $result);
    }
    public function testGetWithConstructorButNoParams()
    {
        $di = new ReflectionDi();
        $result = $di->get('Eschrade\AsyncPack\Test\NoParamConstructorObject');
        $this->assertInstanceOf('Eschrade\AsyncPack\Test\NoParamConstructorObject', $result);
    }
    public function testGetWithConstructorAndParams()
    {
        $di = new ReflectionDi();
        $result = $di->get('Eschrade\AsyncPack\Test\ParamObject', ['param' => 1]);
        $this->assertInstanceOf('Eschrade\AsyncPack\Test\ParamObject', $result);
        $this->assertEquals(1, $result->param);
    }

    public function testGetWithConstructorAndExtraParams()
    {
        $di = new ReflectionDi();
        $result = $di->get('Eschrade\AsyncPack\Test\ParamObject', ['boogers' => 34, 'param' => 1]);
        $this->assertInstanceOf('Eschrade\AsyncPack\Test\ParamObject', $result);
        $this->assertEquals(1, $result->param);
    }

    public function testGetWithConstructorParamsWithNoneProvidedExpectsException()
    {
        $this->setExpectedException('Eschrade\AsyncPack\MissingParameterException');
        $di = new ReflectionDi();
        $result = $di->get('Eschrade\AsyncPack\Test\ParamObject');
        $this->assertInstanceOf('Eschrade\AsyncPack\Test\ParamObject', $result);
        $this->assertEquals(1, $result->param);
    }

}

class NoParamObject {

}
class NoParamConstructorObject {

    public function __construct()
    {
    }

}

class ParamObject {

    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

}