<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Managers\ManagerContract;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommandTestCase extends TestCase
{
    protected ConfigFactory&Stub $configFactory;
    protected ManagerContract&MockObject $manager;
    protected InputInterface $input;
    protected OutputInterface $output;

    protected function setUp(): void
    {
        $this->configFactory = $this->createStub(ConfigFactory::class);

        $this->manager = $this->createMock(ManagerContract::class);

        $this->input = new StringInput('');
        $this->output = new BufferedOutput;
    }
}
