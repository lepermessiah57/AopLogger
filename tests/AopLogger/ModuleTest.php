<?php

namespace AopLoggerTest;

use AopLogger\Module;
use Zend\ServiceManager\ServiceManager;
use Phake;

class ModuleTest extends \PHPUnit_Framework_TestCase {

    private $module;

    public function setUp(){
        $this->module = new Module();
    }

    public function testGetConfigReturnAnArrayOfConfig(){
		$config = $this->module->getConfig();

		$this->assertInternalType('array', $config);
    }

    public function testGetAutoloaderConfigContainsTheNamespace(){
    	$autoloaderConfig = $this->module->getAutoloaderConfig();

    	$namespaces = $autoloaderConfig['Zend\Loader\StandardAutoloader'];
    	$this->assertArrayHasKey('AopLogger', $namespaces['namespaces']);
    }

    public function testGetServiceConfigContainsTheDebugAspectInvokable(){
        $expected = 'AopLogger\Log\DebugAspect';

        $serviceConfig = $this->module->getServiceConfig();

        $this->assertEquals($expected, $serviceConfig['invokables']['DebugAspect']);
    }

    public function testGetServiceConfigContainsTheErrorLogFactory(){
        $expected = 'AopLogger\Factory\ErrorLogFactory';

        $serviceConfig = $this->module->getServiceConfig();

        $this->assertEquals($expected, $serviceConfig['factories']['AopLoggerErrorLog']);
    }

    public function testGetServiceConfigContainsTheKernel(){
        $expected = 'AopLogger\Factory\ApplicationAspectKernelFactory';

        $serviceConfig = $this->module->getServiceConfig();

        $this->assertEquals($expected, $serviceConfig['factories']['ApplicationAspectKernel']);
    }

    public function testGetServiceConfigContainsTheErrorLogInitializer(){
        $expected = 'AopLogger\Initializer\ErrorLogInitializer';

        $serviceConfig = $this->module->getServiceConfig();

        $this->assertEquals($expected, $serviceConfig['initializers']['AopLoggerErrorLogInitializer']);
    }

    public function testOnBootstrapInitializeTheAopKernel(){
    	$debug = false;
    	$cache = null;
    	$whitelist = null;
    	$config = array('AopLogger' => array('Debug'=>$debug, 'Cache' => $cache, 'WhiteList' => $whitelist, 'Disabled'=>false));

    	$serviceManager = new ServiceManager();
    	$serviceManager->setService('config', $config);
    	$event = Phake::mock('Zend\Mvc\MvcEvent');
    	$application = Phake::mock('Zend\Mvc\Application');
    	Phake::when($event)->getApplication()->thenReturn($application);
    	Phake::when($application)->getServiceManager()->thenReturn($serviceManager);
    	$kernel = Phake::mock('AopLogger\Kernel\ApplicationAspectKernel');
    	$serviceManager->setService('ApplicationAspectKernel', $kernel);

    	$this->module->onBootstrap($event);

    	Phake::verify($kernel)->init(array('debug'=>$debug, 'cacheDir'=>$cache, 'includePaths'=>$whitelist));
    }

    public function testOnBootstrapDoNotInitializeTheAopKernelIfDisabledFlagSetToTrue(){
        $debug = false;
        $cache = null;
        $whitelist = null;
        $config = array('AopLogger' => array('Debug'=>$debug, 'Cache' => $cache, 'WhiteList' => $whitelist, 'Disabled'=>true));

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', $config);
        $event = Phake::mock('Zend\Mvc\MvcEvent');
        $application = Phake::mock('Zend\Mvc\Application');
        Phake::when($event)->getApplication()->thenReturn($application);
        Phake::when($application)->getServiceManager()->thenReturn($serviceManager);
        $kernel = Phake::mock('AopLogger\Kernel\ApplicationAspectKernel');
        $serviceManager->setService('ApplicationAspectKernel', $kernel);

        $this->module->onBootstrap($event);

        Phake::verify($kernel, Phake::never())->init(array('debug'=>$debug, 'cacheDir'=>$cache, 'includePaths'=>$whitelist));
    }
}
 