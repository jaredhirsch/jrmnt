<?php

class PassedTestException extends TestException
{
    public function getStatus()
    {
        return 'passed';
    }
}
