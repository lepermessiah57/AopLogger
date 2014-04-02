<?php

namespace AopLoggingTest\Log;

use AopLogging\Log\DebugAspect;
use \Phake;

class DebugAspectTest extends \PHPUnit_Framework_TestCase {

    private $logger;
    private $aspect;
    private $methodInvokation;

    public function setUp(){
        $this->aspect = new DebugAspect();
        $this->logger = Phake::mock('Zend\Log\Logger');
        $this->aspect->setLogger($this->logger);
        $this->methodInvokation = Phake::mock('Go\Aop\Intercept\MethodInvocation');
        $reflection = new \ReflectionMethod('Zend\Log\Logger', 'debug');
        Phake::when($this->methodInvokation)->getMethod()->thenReturn($reflection);
    }

    public function testIsAnAspect(){
        $this->assertInstanceOf('Go\Aop\Aspect', $this->aspect);
    }

    public function testAroundLoggableWillExecuteAMethod(){
        $this->aspect->aroundLoggable($this->methodInvokation);

        Phake::verify($this->methodInvokation)->proceed();
    }

    public function testAroundLoggableWillDebugTheMethodNameAndParameters(){
        $this->aspect->aroundLoggable($this->methodInvokation);

        $name = $this->methodInvokation->getMethod()->name;
        Phake::verify($this->logger)->debug("Entering " . $name, $this->methodInvokation->getArguments());
    }

    public function testAroundLoggableWillDebugOnSuccess(){
        $result = "foo";
        Phake::when($this->methodInvokation)->proceed()->thenReturn($result);
        $this->aspect->aroundLoggable($this->methodInvokation);

        $name = $this->methodInvokation->getMethod()->name;
        Phake::verify($this->logger)->debug("Success: " . $name);
    }

    public function testAroundLoggableWillLogTheExceptionWhenThrown(){
        $exception = new \Exception();
        Phake::when($this->methodInvokation)->proceed()->thenThrow($exception);
        try{
            $this->aspect->aroundLoggable($this->methodInvokation);
        }catch(\Exception $e){
            //expected
        }

        $name = $this->methodInvokation->getMethod()->name;
        Phake::verify($this->logger)->debug("Error: " . $name . " details: " . $exception);
    }
}
 