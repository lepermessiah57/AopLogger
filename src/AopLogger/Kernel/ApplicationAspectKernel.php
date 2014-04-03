<?php

namespace AopLogger\Kernel;

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
        $aopLogging = $config['AopLogging'];
        $aspects = $aopLogging['Aspects'];
        if(array_key_exists('CustomAspects', $aopLogging)){
            $aspects = array_merge($aspects, $aopLogging['CustomAspects']);
        }
        foreach($aspects as $aspect){
            $container->registerAspect($this->serviceLocator->get($aspect));
        }
    }
}