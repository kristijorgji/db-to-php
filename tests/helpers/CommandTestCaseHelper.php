<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Helpers;

use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use function fclose;
use function feof;
use function fread;
use function fseek;
use function tmpfile;

trait CommandTestCaseHelper
{
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
