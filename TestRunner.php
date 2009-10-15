<?php

class TestRunner
{
    public function findTests(UnitTest $testClass)
    {
        $tests = array();
        $className = get_class($testClass);
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

    public function runAllTests(UnitTest $testClass,
                                TestClassResult $allResults = null)
    {
        if ($allResults === null) {
            $allResults = new TestClassResult;
        }
        $tests = $testClass->findTests($testClass);
        $allResults->setClass(get_class($testClass));
        foreach ($tests as $test) {
            $result = $this->runTest($test); 
            $allResults->addResult($result);
        }
        return $allResults;
    }
    
}
