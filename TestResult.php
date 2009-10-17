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
        if (($status !== 'passed') &&
            ($status !== 'failed') && 
            ($status !== 'skipped')) {
            throw new DomainException('test status must be passed, failed, or skipped. You said ' . $status);
        }
        $this->testStatus = $status;
    }

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
