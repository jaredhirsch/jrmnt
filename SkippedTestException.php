<?php

class SkippedTestException extends TestException 
{
    public function getStatus()
    {
        return 'skipped';
    }
}
