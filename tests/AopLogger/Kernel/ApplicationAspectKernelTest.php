<?php

namespace AopLoggerTest\Kernel;

use AopLogger\Kernel\ApplicationAspectKernel;
use Zend\ServiceManager\ServiceManager;
use Phake;

class ApplicationAspectKernelTest extends \PHPUnit_Framework_TestCase {
	
	private $serviceLocator;
	private $kernel;

	public function setUp(){
        $this->serviceLocator = new ServiceManager();
		$this->kernel = ApplicationAspectKernel::getInstance();
		$this->kernel->setServiceLocator($this->serviceLocator);
	}

    public function tearDown(){
        $reflected = new \ReflectionClass($this->kernel);
        $property = $reflected->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null);
    }

	public function testIsAnAspectKernel(){
		$this->assertInstanceOf('Go\Core\AspectKernel', $this->kernel);
	}

    public function testIsAServiceLocatorAware(){
        $this->assertInstanceOf('Zend\ServiceManager\ServiceLocatorAwareInterface', $this->kernel);
    }

    public function testAddAspectsFromServiceLocatorWillAddASingleAspectFromTheServiceLocatorConfig(){
        $config = array('AopLogging'=>array('Aspects'=>array('aspect1')));
        $this->serviceLocator->setService('config', $config);
        $aspect = Phake::mock('Go\Aop\Aspect');
        $this->serviceLocator->setService('aspect1', $aspect);
        $container = Phake::mock('Go\Core\AspectContainer');
        $this->kernel->addAllAspectsFromServiceLocator($container);

        Phake::verify($container)->registerAspect($aspect);
    }

    public function testAddAspectsFromServiceLocatorWillAddMultipleAspectsFromTheServiceLocatorConfig(){
        $config = array('AopLogging'=>array('Aspects'=>array('aspect1', 'aspect2')));
        $this->serviceLocator->setService('config', $config);
        $aspect = Phake::mock('Go\Aop\Aspect');
        $aspect2 = Phake::mock('Go\Aop\Aspect');
        $this->serviceLocator->setService('aspect1', $aspect);
        $this->serviceLocator->setService('aspect2', $aspect2);
        $container = Phake::mock('Go\Core\AspectContainer');
        $this->kernel->addAllAspectsFromServiceLocator($container);

        Phake::verify($container)->registerAspect($aspect);
        Phake::verify($container)->registerAspect($aspect2);
    }

    public function testAddAspectsFromServiceLocatorWillAddCustomAspectsAsWell(){
        $config = array('AopLogging'=>array('Aspects'=>array(), 'CustomAspects'=>array('customAspect')));

        $this->serviceLocator->setService('config', $config);
        $aspect = Phake::mock('Go\Aop\Aspect');
        $this->serviceLocator->setService('customAspect', $aspect);

        $container = Phake::mock('Go\Core\AspectContainer');
        $this->kernel->addAllAspectsFromServiceLocator($container);

        Phake::verify($container)->registerAspect($aspect);
    }

}