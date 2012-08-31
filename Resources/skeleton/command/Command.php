<?php

namespace {{ namespace }}\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class {{ class }} extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('arg', InputArgument::OPTIONAL, 'argument description'),
                new InputOption('opt', null, InputOption::VALUE_OPTIONAL, 'option description', 'default value'),
            ))
            ->setDescription('Write command description here')
            ->setHelp(<<<EOT
    Write command help text here
EOT
            )
            ->setName('{{ command }}')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $out = array(sprintf('opt=<info>%s</info>', $input->getOption('opt')));

        if ($in = $input->getArgument('arg')) {
            $out[] = sprintf('arg=<info>%s</info>', $in);
        }

        $output->writeln('Command <info>{{ command }}</info> executed! '.implode(', ', $out));
    }
}
