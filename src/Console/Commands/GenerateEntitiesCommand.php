<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Console\Commands;

use Exception;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;
use const PHP_EOL;

class GenerateEntitiesCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('generate:entities')
            ->setDescription('Generate entities')
            ->setHelp(sprintf(
                '%sGenerates entities based on the database tables%s',
                PHP_EOL,
                PHP_EOL,
            ));
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap($input, $output);

        try {
            $this->outputGenerationResult($output, $this->getManager()->generateEntities());
        } catch (GenerateException $e) {
            $this->outputGenerationResult($output, $e->getPartialResponse());
            throw $e->getPrevious();
        }

        return 0;
    }
}
