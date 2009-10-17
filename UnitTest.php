<?php

require_once 'Autoload.php';

class UnitTest
{
    public function setUp() {}

    public function tearDown() {}

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
}
