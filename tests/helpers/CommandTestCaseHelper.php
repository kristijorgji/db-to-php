<?php

namespace kristijorgji\Tests\Helpers;

use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

trait CommandTestCaseHelper
{
    /**
     * @param DbToPhpApplication $application
     * @param string $input
     * @return string
     */
    public function runCommand(DbToPhpApplication $application, string $input) : string
    {
        $application->setAutoExit(false);

        $fp = tmpfile();
        $input = new StringInput($input);
        $output = new StreamOutput($fp);

        $application->run($input, $output);

        fseek($fp, 0);
        $output = '';
        while (!feof($fp)) {
            $output = fread($fp, 4096);
        }
        fclose($fp);

        return $output;
    }
}
