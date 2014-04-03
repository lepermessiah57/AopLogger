<?php


namespace AopLogging\Initializer;

use Zend\Log\LoggerAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ErrorLogInitializer implements InitializerInterface{

    public function initialize($instance, ServiceLocatorInterface $serviceLocator) {
        if($instance instanceof LoggerAwareInterface){
            $instance->setLogger($serviceLocator->get('AopLoggerErrorLog'));
        }
    }
}