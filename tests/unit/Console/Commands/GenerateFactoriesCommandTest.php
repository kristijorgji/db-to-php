<?php

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\Console\Commands\GenerateFactoriesCommand;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;

class GenerateFactoriesCommandTest extends AbstractCommandTestCase
{
    /**
     * @var GenerateFactoriesCommand
     */
    protected $command;

    public function setUp()
    {
        parent::setUp();
        $this->command = new GenerateFactoriesCommand(
            $this->configFactory,
            self::randomString()
        );
    }

    public function testExecute_on_error()
    {
        $this->mockSelf(['bootstrap', 'outputGenerationResult', 'getManager']);
        $this->command->expects($this->once())
            ->method('getManager')
            ->willReturn($this->manager);

        $partialResponse = new GenerateResponse();
        $partialResponse->addPath('test');

        $exception = new GenerateException(
            'error',
            new \Exception(self::randomString(100)),
            $partialResponse
        );

        $this->manager->expects($this->once())
            ->method('generateFactories')
            ->willThrowException($exception);

        $executeMethod = $this->getPrivateMethod($this->command, 'execute');

        $this->command->expects($this->once())
            ->method('outputGenerationResult')
            ->with($this->output, $exception->getPartialResponse());

        try {
            $executeMethod->invokeArgs($this->command, [
                $this->input,
                $this->output
            ]);
        }catch (\Exception $e) {
            $this->assertEquals($exception->getPrevious(), $e);
        }
    }

    protected function mockSelf(array $methodsToMock)
    {
        $this->command = $this->getMockBuilder(GenerateFactoriesCommand::class)
            ->setConstructorArgs([
                $this->configFactory,
                self::randomString()
            ])
            ->setMethods($methodsToMock)
            ->getMock();
    }
}
