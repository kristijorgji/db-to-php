<?php

namespace kristijorgji\DbToPhp\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateEntitiesCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('generate:entities')
            ->setDescription('Generate entities')
            ->setHelp(sprintf(
                '%sGenerates entities based on the database tables%s',
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
        $this->getManager()->generateEntities();
    }
}