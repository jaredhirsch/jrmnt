<?php

require_once 'Autoload.php';

class BasicTestBehaviorCheck extends UnitTest
{
    /**
     * @Test
     */
    public function goodTest()
    {
        $this->should(1 === 1);
    }

    /**
     * @Test
     */
    public function badTest()
    {
        $this->shouldnt(true);
    }

    /**
     * @Test
     */
    public function passTest()
    {
        $this->pass('pass because I say so');
    }

    /**
     * @Test
     */
    public function failTest()
    {
        $this->fail('fail because I say so');
    }

    /**
     * @Test
     */
    public function skipTest()
    {
        $this->skip('this skip message should appear');
        $this->fail('fail message after skip should NEVER appear');
    }
    
    /** @Test */
    public function ShouldFailEvenIfEarlierShouldsSucceed()
    {
        $this->should(true);
        $this->should(true);
        $this->should(true);
        $this->should(false);
    }
}

//$r = new BasicTestBehaviorCheck;
//$r->runAndReport();
BasicTestBehaviorCheck::runStatic();
