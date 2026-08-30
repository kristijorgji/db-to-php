<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Console\Commands;

use Exception;
use kristijorgji\DbToPhp\Console\Commands\GenerateFactoriesCommand;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;

final class GenerateFactoriesCommandTest extends AbstractCommandTestCase
{
    protected GenerateFactoriesCommand&MockObject $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockSelf([]);
    }

    public function testExecute_on_error(): void
    {
        $this->mockSelf(['bootstrap', 'outputGenerationResult', 'getManager']);
        $this->command->expects($this->once())
            ->method('getManager')
            ->willReturn($this->manager);

        $partialResponse = new GenerateResponse;
        $partialResponse->addPath('test');

        $exception = new GenerateException(
            'error',
            new Exception(self::randomString(100)),
            $partialResponse,
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
                $this->output,
            ]);
        }catch (Throwable $e) {
            $this->assertEquals($exception->getPrevious(), $e);
        }
    }

    protected function mockSelf(array $methodsToMock): void
    {
        $this->command = $this->getMockBuilder(GenerateFactoriesCommand::class)
            ->setConstructorArgs([
                $this->configFactory,
                self::randomString(),
            ])
            ->onlyMethods($methodsToMock)
            ->getMock();
    }
}
