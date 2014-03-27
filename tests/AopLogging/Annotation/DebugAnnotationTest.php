<?php

namespace AopLoggingTest\Annotation;

use AopLogging\Annotation\DebugAnnotation;

class DebugAnnotationTest extends \PHPUnit_Framework_TestCase {

    public function testIsAnAnnotation(){
        $annotation = new DebugAnnotation(array());

        $this->assertInstanceOf('Doctrine\Common\Annotations\Annotation', $annotation);
    }
}
 