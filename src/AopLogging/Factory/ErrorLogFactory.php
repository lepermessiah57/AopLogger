<?php


namespace AopLogging\Factory;


use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ErrorLogFactory implements FactoryInterface{

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $logger = new Logger();
        $writer = new Stream('php://stderr');
        $logger->addWriter($writer);
        return $logger;
    }
}