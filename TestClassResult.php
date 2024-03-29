<?php

class TestClassResult implements IteratorAggregate
{
    protected $results;

    public function __construct()
    {
        $this->results = array();
    }

    public function addResult(TestResult $result)
    {
        $this->results[] = $result;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->results);
    }

    public function hasFailingTests()
    {
        foreach ($this->results as $result) {
            if ($result->isFailure()) {
                return true;
            }
        }
        return false;
    
    }

    protected $testClass;

    public function setClass($className)
    {
        $this->testClass = $className;
    }

    public function getClass()
    {
        return $this->testClass;
    }

}
