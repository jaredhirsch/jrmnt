<?php

class TestClassResult implements IteratorAggregate
{
    protected $testClass;
    public function setClass($className)
    {
        $this->testClass = $className;
    }

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
}
