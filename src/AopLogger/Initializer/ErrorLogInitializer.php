<?php


namespace AopLogger\Initializer;

use AopLogger\Log\ErrorLoggerAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ErrorLogInitializer implements InitializerInterface{

    public function initialize($instance, ServiceLocatorInterface $serviceLocator) {
        if($instance instanceof ErrorLoggerAwareInterface){
            $instance->setLogger($serviceLocator->get('AopLoggerErrorLog'));
        }
    }
}