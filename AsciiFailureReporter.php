<?php

// right now, reporter is just a template.
// once we have results in a Result object,
// might like to move the result-formatting
// code into this class, then use whatever
// template. Basically the idea is to refactor
// towards a ViewHelper + template approach.

// this reporter only tells us about failures
// in detail. and reports ascii designed for
// the command line. So here's an expressive name:
class AsciiFailureReporter implements Reporter
{
    public function report(TestClassResult $results)
    {
        if ($results->hasFailingTests()) {
            $this->printClassInfo($results->getClass());
            $this->printFailures($results);
        }
    }

    protected function printFailures(TestClassResult $results)
    {
        $failedTests = 0;
        foreach($results as $test) {
            if ($test->isFailure()) {
                $this->printFailedTestInfo($test->getTestName(),
                                                $test->getTestMessage());
                $failedTests++;
            }
        }
        $this->printFailureSummary($failedCount = $failedTests, 
                                $totalTestCount = count($results->getIterator()));
        
    }

    public function printHeader() {}
    public function printFooter() {}
    public function printClassInfo($className)
    {
        echo "\n$className failed tests:\n";
    }
    public function printFailedTestInfo($testName, $message = null)
    {
        echo "$testName";
        if ($message) {
            echo " (message: $message)";
        }
        echo ". ";
    }
    public function printSuccessSummary() {}
    public function printFailureSummary($failedTestCount, $totalTestCount)
    {
        echo "\n$failedTestCount failed out of $totalTestCount.\n";
    }
}
