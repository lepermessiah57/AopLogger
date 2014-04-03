<?php

namespace AopLogging;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e){
        $application = $e->getApplication();
        $services = $application->getServiceManager();
        $config = $services->get('config');
        $aopLogging = $config['AopLogging'];

        $applicationAspectKernel = \AopLogging\Kernel\ApplicationAspectKernel::getInstance();
	    $applicationAspectKernel->setServiceLocator($services);
        $applicationAspectKernel->init(array(
            'debug' => $aopLogging['Debug'], // Use 'false' for production mode
            'cacheDir' => $aopLogging['Cache'], // Adjust this path if needed
            'includePaths' => $aopLogging['WhiteList']
        ));
    }
    
    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'DebugAspect'=>'AopLogging\Log\DebugAspect'
            ),
            'factories' => array(
                'AopLoggerErrorLog' => 'AopLogging\Factory\ErrorLogFactory'
            ),
            'initializers' => array(
                'AopLoggerErrorLogInitializer' => 'AopLogging\Initializer\ErrorLogInitializer'
            )
        );
    }
}
