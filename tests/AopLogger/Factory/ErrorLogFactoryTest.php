<?php

namespace AopLoggerTest\Factory;

use AopLogger\Factory\ErrorLogFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\Log\Writer\Stream;	

class ErrorLogFactoryTest extends \PHPUnit_Framework_TestCase {
	
	public function testCreatesAlogger(){
		$factory = new ErrorLogFactory();

		$logger = $factory->createService(new ServiceManager());

		$this->assertInstanceOf('Zend\Log\Logger', $logger);
	}

}