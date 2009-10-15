<?php

require_once 'Autoload.php';

// same thing as JrMnt, but no comments.
// I kind of find them really distracting.
class UnitTest extends TestRunner
{
    public function setUp() 
    {
    }

    public function tearDown() 
    {
    }

    public function skip($message = null)
    {
        throw new SkippedTestException($message);
    }

    public function pass($message = null)
    {
        throw new PassedTestException($message);
    }

    public function fail($message = null)
    {
        throw new FailedTestException($message);
    }

    public function should($boolean)
    {
        if (!($boolean)) {
            throw new FailedTestException();
        }
    }

    public function shouldnt($boolean)
    {
        if ($boolean) {
            throw new FailedTestException();
        }
    }

    public function shouldBeEqual($one, $other)
    {
        if ($one != $other) {
            throw new FailedTestException("$one does not equal $other");
        }
    }

    public function shouldBeIdentical($one, $other)
    {
        if ($one !== $other) {
            throw new FailedTestException("$one is not identical to $other");
        }
    }

    public function findTests()
    {
        return parent::findTests($this);
    }

    protected $allResults;

    public function runAndReport(Reporter $reporter = null)
    {
        $output = $this->runAllTests($this);
        if ($reporter == null) {
            $reporter = new AsciiFailureReporter;
        }
        $reporter->report($output);
    }

    public static function runrun()
    {
        $testClassName = get_called_class();
        $testClass = new $testClassName;
        $testClass->runAndReport();
    }
}
