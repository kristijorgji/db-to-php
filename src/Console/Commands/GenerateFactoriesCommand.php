<?php

namespace kristijorgji\DbToPhp\Console\Commands;

use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFactoriesCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('generate:factories')
            ->setDescription('Generate entity factories')
            ->setHelp(sprintf(
                '%sGenerates entity factories based on the database tables%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap($input, $output);

        try {
            $this->outputGenerationResult($output, $this->getManager()->generateFactories());
        } catch (GenerateException $e) {
            $this->outputGenerationResult($output, $e->getPartialResponse());
            throw $e->getPrevious();
        }

        return 0;
    }
}
