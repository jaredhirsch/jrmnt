<?php

class TestRunner
{
    public function findTests()
    {
        $tests = array();
        // have to change this for move to parent
        // $className = get_class($this);
        $className = get_called_class();
        $metaClass = new ReflectionClass($className);

        foreach ($metaClass->getMethods() as $metaMethod) {
            $comment = $metaMethod->getDocComment();
            if ($this->commentContainsTestFlag($comment)) {
                $tests[] = $metaMethod->getName();
            }
        }
    
        return $tests;
    }

    private function commentContainsTestFlag($comment)
    {
        return (($comment != false) && 
                (strpos($comment, '@Test') !== false));
    }

    public function runTest($test, UnitTest $testClass, TestResult $result = null)
    {
        if ($result === null) {
            $result = new TestResult;
        }
        $result->setTestName($test);
        $testClass->setUp();
        try {
            $testClass->$test();
            $result->setTestStatus('passed');
        } catch (TestException $e) {
            $result->setTestStatus($e->getStatus());
            $result->setTestMessage($e->getMessage());
        }
        $testClass->tearDown();
        return $result;
    }

    
}
