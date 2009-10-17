<?php

require_once 'Autoload.php';

class BasicTestBehaviorCheck extends UnitTest
{
    /**
     * @Test
     */
    public function equalityTest()
    {
        $this->shouldBeEqual(1, 1);
    }

    /**
     * @Test
     */
    public function equalityFailureTest()
    {
        try {
            $this->shouldBeEqual(1, 2);
        } catch (FailedTestException $e) {
            $this->pass();
        }
    }

    /**
     * @Test
     */
    public function identicalityTest()
    {
        $this->shouldBeIdentical(1, 1);
    }

    /**
     * @Test
     */
    public function identicalityFailureTest()
    {
        try {
            $this->shouldBeIdentical(1, '1');
        } catch (FailedTestException $e) {
            $this->pass();
        }
    }

    /**
     * @Test
     */
    public function passTest()
    {
        $this->pass();
        $this->fail('should immediately halt execution after pass()');
    }

    /**
     * @Test
     */
    public function skipTest()
    {
        $this->skip();
        $this->fail('should immediately halt execution after skip()');
    }

    /**
     * @Test
     */
    public function failTest()
    {
        try {
            $this->fail('expected failure');
            $this->fail('should never get here');
        } catch (FailedTestException $e) {
            if ($e->getMessage() == 'should never get here') {
                $this->fail();
            }
        }
    }

    /** @Test */
    public function shouldFailEvenIfEarlierShouldsSucceed()
    {
        $this->shouldBeEqual(1,1);
        try {
            $this->shouldBeEqual(1,2);
        } catch (FailedTestException $e) {}
    }

    /* @Test */
    public function shouldNotRunTestIfCommentIsBlockInsteadOfDoccomment()
    {
        $this->fail();
    }

    /** @ Test */
    public function shouldNotRunTestIfCommentContainsASpace()
    {
        $this->fail();
    }
}

//$r = new BasicTestBehaviorCheck;
//$r->runAndReport();
TestRunner::runStatic(new BasicTestBehaviorCheck, new AsciiFailureReporter);
