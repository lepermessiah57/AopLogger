<?php 

namespace AopLoggerTest\Initializer;

use Phake;
use AopLogger\Initializer\ErrorLogInitializer;
use Zend\serviceManager\serviceManager;

class ErrorLogInitializerTest extends \PHPUnit_Framework_TestCase {
	
	private $initializer;
	private $serviceManager;
	private $logger;

	public function setUp(){
		$this->initializer = new ErrorLogInitializer();
		$this->logger = new \Zend\Log\Logger;
		$this->serviceManager = new serviceManager();
		$this->serviceManager->setService('AopLoggerErrorLog', $this->logger);
	}

	public function testSetErrorLogOnAwareClass(){
		$instance = Phake::mock('AopLogger\Log\ErrorLoggerAwareInterface');

		$this->initializer->initialize($instance, $this->serviceManager);

		Phake::verify($instance)->setLogger($this->logger);
	}

	public function testDoNotSetErrorLogOnNonAwareClass(){
		$instance = Phake::mock('Zend\Log\LoggerAwareInterface');

		$this->initializer->initialize($instance, $this->serviceManager);

		Phake::verify($instance, Phake::never())->setLogger($this->logger);	
	}
}