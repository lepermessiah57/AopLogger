<?php

namespace AopLogger\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationAspectKernelFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
		$kernel = \AopLogger\Kernel\ApplicationAspectKernel::getInstance();
		$kernel->setServiceLocator($serviceLocator);
    }
}