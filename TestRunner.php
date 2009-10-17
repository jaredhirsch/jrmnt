<?php

class TestRunner
{
    public static function runStatic(UnitTest $testClass,
                                     Reporter $reporter = null)
    {
        $tr = new TestRunner;
        $tr->runAndReport($testClass, $reporter);
    }

    public function runAndReport(UnitTest $testClass,
                                 Reporter $reporter = null)
    {
        $output = $this->runAllTests($testClass);
        if ($reporter == null) {
            $reporter = new AsciiFailureReporter;
        }
        $reporter->report($output);
    }

    public function runAllTests(UnitTest $testClass,
                                TestClassResult $testResults = null)
    {
        if ($testResults === null) {
            $testResults = new TestClassResult;
        }
        $tests = $this->findTests($testClass);
        $testResults->setClass(get_class($testClass));
        foreach ($tests as $test) {
            $result = $this->runTest($test, $testClass); 
            $testResults->addResult($result);
        }
        return $testResults;
    }

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

    public function runTest($test, UnitTest $testClass, 
                            TestResult $result = null)
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

    private function commentContainsTestFlag($comment)
    {
        return (($comment != false) && 
                (strpos($comment, '@Test') !== false));
    }

}
