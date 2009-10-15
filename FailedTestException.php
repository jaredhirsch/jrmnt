<?php

class FailedTestException extends TestException
{
    public function getStatus()
    {
        return 'failed';
    }
}
