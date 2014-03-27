<?php

namespace AopLogging\Kernel;

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationAspectKernel extends AspectKernel implements ServiceLocatorAwareInterface{

	private $serviceLocator;

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
	}

    public function getServiceLocator(){
    	return $this->serviceLocator;
    }

	protected function configureAop(AspectContainer $container){
        $this->addAllAspectsFromServiceLocator($container);
    }

    public function addAllAspectsFromServiceLocator(AspectContainer $container){
        $config = $this->serviceLocator->get('config');
        $aspects = $config['AopLoggingAspects'];
        if(array_key_exists('AopLoggingCustomAspects', $config)){
            $aspects = array_merge($aspects, $config['AopLoggingCustomAspects']);
        }
        foreach($aspects as $aspect){
            $container->registerAspect($this->serviceLocator->get($aspect));
        }
    }
}