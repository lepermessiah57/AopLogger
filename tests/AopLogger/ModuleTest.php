<?php

namespace AopLoggerTest;

use AopLogger\Module;

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
}
 