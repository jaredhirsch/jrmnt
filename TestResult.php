<?php

class TestResult
{
    protected $testName;
    public function setTestName($testName)
    {
        $this->testName = $testName;
    }

    protected $testStatus;
    public function setTestStatus($status)
    {
        if ($status !== ('passed' || 'failed' || 'skipped')) {
            throw new BadInputException('test status must be passed, failed, or skipped');
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
}
