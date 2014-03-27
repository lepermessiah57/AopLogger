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

        $applicationAspectKernel = \AopLogging\Kernel\ApplicationAspectKernel::getInstance();
        $applicationAspectKernel->init(array(
            'debug' => $config['AopLoggingDebug'], // Use 'false' for production mode
            'cacheDir' => $config['AopLoggingCache'], // Adjust this path if needed
            'includePaths' => $config['AopLoggingWhiteList']
        ));
    }
    
    public function getServiceConfig() {
        return array('invokables'=>array('DebugAspect'=>'AopLogging\Log\DebugAspect'));
    }
}
