<?php

namespace AopLogger\Kernel;

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationAspectKernel extends AspectKernel implements ServiceLocatorAwareInterface{

	private $serviceLocator;
    protected static $initialized = false;

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
        $config = $this->getServiceLocator()->get('config');
        $aopLogging = $config['AopLogger'];
        $aspects = $aopLogging['Aspects'];
        if(array_key_exists('CustomAspects', $aopLogging)){
            $aspects = array_merge($aspects, $aopLogging['CustomAspects']);
        }
        foreach($aspects as $aspect){
            $container->registerAspect($this->serviceLocator->get($aspect));
        }
    }

    public function init(array $options = array()){
        if(!ApplicationAspectKernel::$initialized){
            parent::init($options);
        }
        ApplicationAspectKernel::$initialized = true;
    }
}