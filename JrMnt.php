<?php

require_once 'Autoload.php';

class JrMnt
{

// the ubiquitous setup and teardown methods used
// to prepare and recover from test execution.

    /**
     * setUp - used to initialize resources required 
     *         for each test in a given test class.
     * 
     * @access public
     * @return void
     */
    public function setUp() 
    {
    }

    /**
     * tearDown - overridden in actual test cases to release
     *            resources used in each test. Generally acts
     *            as a counterpoint to setUp. Note that uncaught
     *            errors and exceptions will stop the test framework,
     *            so that tearDown may not be called after a test.
     * 
     * @access public
     * @return void
     */
    public function tearDown() 
    {
    }

// some convenience functions to halt execution immediately

    /**
     * skip - marks test as 'skipped' and halts test execution
     * 
     * @param mixed $message 
     * @access public
     * @return void
     */
    public function skip($message = null)
    {
        throw new SkippedTestException($message);
    }

    /**
     * pass - marks test as 'passed' and halts test execution
     * 
     * @param mixed $message 
     * @access public
     * @return void
     */
    public function pass($message = null)
    {
        throw new PassedTestException($message);
    }

    /**
     * fail - marks test as 'failed' and halts test execution
     * 
     * @param mixed $message 
     * @access public
     * @return void
     */
    public function fail($message = null)
    {
        throw new FailedTestException($message);
    }

// some functions which actually test the state of the world

    /**
     * should - Expects argument to evaluate to true. This method
     *          comes from the original Beck Test Framework. I
     *          happen to prefer it to AssertTrue, the equivalent
     *          xUnit function.
     * 
     * @param mixed $boolean 
     * @access public
     * @return void
     */
    public function should($boolean)
    {
        if (!($boolean)) {
            throw new FailedTestException();
        }
    }

    /**
     * shouldnt - Expects argument to evaluate to false. This method
     *            also comes from the original Beck Test Framework.
     * 
     * @param mixed $boolean 
     * @access public
     * @return void
     */
    public function shouldnt($boolean)
    {
        if ($boolean) {
            throw new FailedTestException();
        }
    }

    /**
     * shouldBeEqual - more along the lines of jUnit assertFoo() tests,
     *                 the advantage is that we can report the compared
     *                 values on failure. If an expression is passed in,
     *                 it's evaluated when it's converted to string, so
     *                 it's not easy to get the expression as string.
     * 
     * @param mixed $one 
     * @param mixed $other 
     * @access public
     * @return void
     */
    public function shouldBeEqual($one, $other)
    {
        if ($one != $other) {
            throw new FailedTestException("$one does not equal $other");
        }
    }

    /**
     * shouldBeIdentical - like shouldBeEqual, except uses stricter
     *                     identity comparison instead of equality
     *                     comparison.
     * 
     * @param mixed $one 
     * @param mixed $other 
     * @access public
     * @return void
     */
    public function shouldBeIdentical($one, $other)
    {
        if ($one !== $other) {
            throw new FailedTestException("$one is not identical to $other");
        }
    }

// core test-related utility functions

    /**
     * findTests - find tests in file. Currently checks
     *             doccomment for the string '@' . 'Test'.
     *             Left public for ease of testing. I
     *             happen to think this is a particularly
     *             beautiful method.
     * 
     * @access public
     * @return void
     */
    public function findTests()
    {
        $tests = array();
        $className = get_class($this);
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

    /**
     * allResults - results of each test run 
     *              are appended to this Iterator
     * 
     * @var mixed
     * @access protected
     */
    protected $allResults;

    /**
     * runTest - run just one test in a test class.
     *           Return results as a TestResult, or
     *           pass in a subclass if you like.
     *
     * @access public
     * @return TestResult
     */
    public function runTest($test, TestResult $result = null)
    {
        if ($result === null) {
            $result = new TestResult;
        }
        $result->setTestName($test);
        $this->setUp();
        try {
            $this->$test();
            $result->setTestStatus('passed');
        } catch (TestException $e) {
            $result->setTestStatus($e->getStatus());
            $result->setTestMessage($e->getMessage());
        }
        $this->tearDown();
        return $result;
    }

    /**
     * runAllTests - discover tests in self, run
     *               each, and capture the TestResult
     *               from each run into a TestClassResult.
     *               Optionally, pass in your own object
     *               to capture results into.
     * 
     * @access public
     * @return object allResults
     */
    public function runAllTests(TestClassResult $allResults = null)
    {
        if ($allResults === null) {
            $allResults = new TestClassResult;
        }
        $tests = $this->findTests();
        $allResults->setClass(get_class($this));
        foreach ($tests as $test) {
            $result = $this->runTest($test); 
            $allResults->addResult($result);
        }
        return $allResults;
    }

    /**
     * runAndReport - run()s all the tests and also displays the
     *                results. Default Reporter just sends the
     *                ascii output to what's assumed to be command
     *                line interface.
     * 
     * @access public
     * @return void
     */
    public function runAndReport(Reporter $reporter = null)
    {
        $output = $this->runAllTests();
        if ($reporter == null) {
            $reporter = new AsciiFailureReporter;
        }
        $reporter->report($output);
    }

    /**
     * runrun - Just a static convenience method to allow
     *          runAndReport to be called without instantiating
     *          the class first.
     *
     * @access public
     * @return void
     */
    public static function runrun()
    {
        $testClassName = get_called_class();
        $testClass = new $testClassName;
        $testClass->runAndReport();
    }

}
