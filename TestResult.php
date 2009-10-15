<?php

class TestResult
{
    protected $testName;
    public function setTestName($testName)
    {
        $this->testName = $testName;
    }
    public function getTestName()
    {
        return $this->testName;
    }

    protected $testStatus;
    public function setTestStatus($status)
    {
        // I wanted to say: if a is not b, c, or d.
        // but having a little trouble with bitwise 
        // operators, so using the contrapositive.
        // todo: clean up.
        if (($status !== 'passed') &&
            ($status !== 'failed') && 
            ($status !== 'skipped')) {
            throw new DomainException('test status must be passed, failed, or skipped. You said ' . $status);
        }
        $this->testStatus = $status;
    }
    // used by TestClassResult to determine
    // if the test was a failure or not.
    public function isFailure()
    {
        return ($this->testStatus === 'failed');
    }

    protected $testMessage;
    public function setTestMessage($message)
    {
        $this->testMessage = $message;
    }
    public function getTestMessage()
    {
        return $this->testMessage;
    }
}
