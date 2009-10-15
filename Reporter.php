<?php

interface Reporter
{
    public function printHeader();
    public function printFooter();

    public function printClassInfo($className);
    public function printFailedTestInfo($testName, $message = null);

    public function printSuccessSummary();
    public function printFailureSummary($failedTestCount, $totalTestCount);

    public function report(TestClassResult $results);
}
