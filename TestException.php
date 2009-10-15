<?php

abstract class TestException extends Exception
{
    // quoth Pro PHP: "you must be careful to call 
    // the base class’s constructor, as failing
    // to do this can result in unreliable and 
    // often unstable behavior within PHP" :-)
    public function __construct($message = null, $errorCode = 0)
    {
        parent::__construct($message, $errorCode);
    }

    abstract public function getStatus();
}
