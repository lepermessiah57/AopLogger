<?php

namespace AopLogger;

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
        $aopLogging = $config['AopLogger'];
        if(!$aopLogging['Disabled']){
            $applicationAspectKernel = $services->get('ApplicationAspectKernel');
            $applicationAspectKernel->init(array(
                'debug' => $aopLogging['Debug'], // Use 'false' for production mode
                'cacheDir' => $aopLogging['Cache'], // Adjust this path if needed
                'includePaths' => $aopLogging['WhiteList']
            ));
        }
    }
    
    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'DebugAspect'=>'AopLogger\Log\DebugAspect'
            ),
            'factories' => array(
                'AopLoggerErrorLog' => 'AopLogger\Factory\ErrorLogFactory',
                'ApplicationAspectKernel' => 'AopLogger\Factory\ApplicationAspectKernelFactory'
            ),
            'initializers' => array(
                'AopLoggerErrorLogInitializer' => 'AopLogger\Initializer\ErrorLogInitializer'
            )
        );
    }
}
