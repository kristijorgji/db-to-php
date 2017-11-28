<?php

namespace kristijorgji\DbToPhp\Console\Commands;

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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);
        $this->getManager()->generateFactories();
    }
}
