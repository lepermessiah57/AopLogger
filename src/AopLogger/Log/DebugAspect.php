<?php

namespace AopLogger\Log;

use Go\Aop\Aspect;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

class DebugAspect implements Aspect, LoggerAwareInterface{

    private $logger;

    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @Pointcut("@annotation(AopLogger\Annotation\DebugMethod)")
     */
    protected function loggablePointcut() {}

    /**
     * @Around("$this->loggablePointcut")
     */
    public function aroundLoggable(MethodInvocation $invocation)
    {
        $method = $invocation->getMethod()->name;
        $this->logger->debug("Entering " . $method, $invocation->getArguments());
        try {
            $result = $invocation->proceed();
            $this->logger->debug("Success: " . $method );
        } catch (\Exception $e) {
            $this->logger->debug("Error: " . $method . ' details: ' . $e);
            throw $e;
        }
        return $result;
    }
}