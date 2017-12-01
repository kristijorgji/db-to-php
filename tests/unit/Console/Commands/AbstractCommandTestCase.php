<?php

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Managers\ManagerContract;
use kristijorgji\Tests\Helpers\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommandTestCase extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $manager;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function setUp()
    {
        $this->configFactory = $this->getMockBuilder(ConfigFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->manager = $this->getMockBuilder(ManagerContract::class)->getMock();

        $this->input = new StringInput('');
        $this->output = new BufferedOutput();
    }
}
