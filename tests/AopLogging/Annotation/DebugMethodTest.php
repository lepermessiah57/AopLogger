<?php

namespace AopLoggingTest\Annotation;

use AopLogging\Annotation\DebugMethod;

class DebugMethodTest extends \PHPUnit_Framework_TestCase {

    public function testIsAnAnnotation(){
        $annotation = new DebugMethod(array());

        $this->assertInstanceOf('Doctrine\Common\Annotations\Annotation', $annotation);
    }
}
 