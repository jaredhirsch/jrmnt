<?php

// this is the old AsciiFailureReporter. It's actually
// really verbose by unix standards ('be silent unless you fail').

// I know we have potential for an abstract parent here,
// but just inheriting directly for now.
class VerboseAsciiFailureReporter extends AsciiFailureReporter
{

    public function report(TestClassResult $results)
    {
        $this->printHeader();
        if ($results->hasFailingTests()) {
            $this->printClassInfo($results->getClass());
            $this->printFailures($results);
        }
        $this->printFooter();
    }


    public function printHeader()
    {
        echo <<<EOT
  __                   _   
  \ \ _ __ /\/\  _ __ | |_
   \ \ '__/    \| '_ \| __|
/\_/ / | / /\/\ \ | | | |_ 
\___/|_| \/    \/_| |_|\__|

EOT;
    }

    public function printFooter()
    {
        echo "Finished run at " . date('h:i:s A.');
        echo "\n\n";
    }

    public function printSuccessSummary()
    {
        echo <<<EOT
                             ___  _    __  __    _ 
*************************** / _ \/_\  / _\/ _\  / \ 
************************** / /_)//_|\ \ \ \ \  /  /
************************* / ___/  _  \_\ \_\ \/\_/ 
************************  \/   \_/ \_/\__/\__/\/


EOT;
    }

    public function printClassInfo($className)
    {
        echo "Test Case: " . $className;
        echo "\n\n";
        echo "failing tests:";
        echo "\n\n";
    }

    public function printFailedTestInfo($testName, $failureMessage = null)
    {
        echo $testName . "\n";
        echo "    " . $failureMessage . "\n";
    }

    public function printFailureSummary($numberOfFailedTests, 
                                            $totalNumberOfTests)
    {
        echo "\n";
        echo "SUMMARY:";
        echo "\n";
        echo ">>>> " . $numberOfFailedTests . " failed.";
        echo "\n";
        echo ">>>> " . $totalNumberOfTests . " total.";
        echo "\n";
    }
}
