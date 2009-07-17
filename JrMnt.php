<?php

abstract class TestException extends Exception
{
    // quoth Pro PHP: "you must be careful to call 
    // the base class’s constructor, as failing
    // to do this can result in unreliable and 
    // often unstable behavior within PHP" :-)
    public function __construct($message = null, $errorCode = 0)
    {
        parent::__construct($message, $errorCode);
    }

    abstract public function getStatus();
}

class SkippedTestException extends TestException 
{
    public function getStatus()
    {
        return 'skipped';
    }
}

class PassedTestException extends TestException
{
    public function getStatus()
    {
        return 'passed';
    }
}

class FailedTestException extends TestException
{
    public function getStatus()
    {
        return 'failed';
    }
}

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
     *             left public for ease of testing.
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
     *              are appended to this array
     * 
     * @var mixed
     * @access protected
     */
    protected $allResults;

    /**
     * run - runs all tests, returning array with all the
     *       results. Really want to move towards a result
     *       object, but one step at a time.
     * 
     * @access public
     * @return array allResults
     */
    public function run()
    {
        $tests = $this->findTests();

        $this->allResults['metadata']['class'] = get_class($this);

        foreach ($tests as $test) {

            $this->result = array();
            $this->result['name'] = $test;

            $this->setUp();
            try {
                $this->$test();
                $this->result['status'] = 'passed';
            } catch (TestException $e) {
                $this->result['status'] = $e->getStatus();
                $this->result['message'] = $e->getMessage();
            }
            $this->tearDown();

            $this->allResults['tests'][] = $this->result;
        }
        return $this->allResults;
    }

    private function hasFailingTests($output)
    {
        foreach ($output['tests'] as $test) {
            if ($test['status'] === 'failed') {
                return true;
            }
        }
        return false;
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
        $output = $this->run();

        if ($reporter == null) {
            $reporter = new AsciiFailureReporter;
        }

        $reporter->printHeader();

        if ($this->hasFailingTests($output)) {
            $reporter->printClassInfo($output['metadata']['class']);
            
            $failedTests = 0;
            foreach ($output['tests'] as $test) {
                if ($test['status'] == 'failed') {
                    $reporter->printFailedTestInfo($test['name'],
                                                    $test['message']);
                    $failedTests++;
                }
            }
        
            $reporter->printFailureSummary($failedCount = $failedTests, 
                                    $totalTestCount = count($output['tests']));
        } else {
            $reporter->printSuccessSummary();
        }
        
        $reporter->printFooter();
    }

}

interface ReporterInterface
{
    public function printHeader();
    public function printFooter();

    public function printClassInfo($className);
    public function printFailedTestInfo($testName, $message = null);

    public function printSuccessSummary();
    public function printFailureSummary($failedTestCount, $totalTestCount);
}

// right now, reporter is just a template.
// once we have results in a Result object,
// might like to move the result-formatting
// code into this class, then use whatever
// template. Basically the idea is to refactor
// towards a ViewHelper + template approach.

// this reporter only tells us about failures
// in detail. and reports ascii designed for
// the command line. So here's an expressive name:
class AsciiFailureReporter implements ReporterInterface
{

    public function printHeader()
    {
        echo <<<EOT
  __                   _   
  \ \ _ __ /\/\  _ __ | |_
   \ \ '__/    \| '_ \| __|
/\_/ / | / /\/\ \ | | | |_ 
\___/|_| \/    \/_| |_|\__|

EOT;
    }

    public function printFooter()
    {
        echo "Finished run at " . date('h:i:s A.');
        echo "\n\n";
    }

    public function printSuccessSummary()
    {
        echo <<<EOT
                             ___  _    __  __    _ 
*************************** / _ \/_\  / _\/ _\  / \ 
************************** / /_)//_|\ \ \ \ \  /  /
************************* / ___/  _  \_\ \_\ \/\_/ 
************************  \/   \_/ \_/\__/\__/\/


EOT;
    }

    public function printClassInfo($className)
    {
        echo "Test Case: " . $className;
        echo "\n\n";
        echo "failing tests:";
        echo "\n\n";
    }

    public function printFailedTestInfo($testName, $failureMessage = null)
    {
        echo $testName . "\n";
        echo "    " . $failureMessage . "\n";
    }

    public function printFailureSummary($numberOfFailedTests, 
                                            $totalNumberOfTests)
    {
        echo "\n";
        echo "SUMMARY:";
        echo "\n";
        echo ">>>> " . $numberOfFailedTests . " failed.";
        echo "\n";
        echo ">>>> " . $totalNumberOfTests . " total.";
        echo "\n";
    }
}
