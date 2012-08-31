<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Sensio\Bundle\GeneratorBundle\Generator\CommandGenerator;

/**
 * Generates a console command class
 */
class GenerateCommandCommand extends ContainerAwareCommand
{
    /**
     * @var CommandGenerator
     */
    private $generator;

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Bundle to create the console command for'),
                new InputArgument('command_name', InputArgument::REQUIRED, 'Command name'),
                new InputOption('class', null, InputOption::VALUE_REQUIRED, 'Command class'),
            ))
            ->setDescription('Generates a console command inside a bundle')
            ->setHelp(<<<EOT
The <info>generate:command</info> command generates a class to execute a console command in a bundle.

<info>php app/console generate:command AcmeDemoBundle my:test-task</info>

Creates a class MyTestTaskCommand inside the Command folder of AcmeDemoBundle. Use the --class option to specify a custom class name.

<info>php app/console generate:command AcmeDemoBundle my:test-task --class=MyCustomCommand</info>
EOT
            )
            ->setName('generate:command')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle  = $this->getContainer()->get('kernel')->getBundle($input->getArgument('bundle'));
        $command = $input->getArgument('command_name');

        if (preg_match('/[^a-z0-9\:\-]/i', $command)) {
            throw new \InvalidArgumentException('The command name contains invalid characters (only letters, numbers "-", ":" are allowed).');
        }

        if (!$class = $input->getOption('class')) {
            $class = preg_replace_callback('/(^|\-|\:)+(.)/', function ($match) { return strtoupper($match[2]); }, $command);
        }

        if (!preg_match('/Command$/', $class)) {
            $class .= 'Command';
        }

        $generator = $this->getGenerator();
        $generator->generate($bundle, $command, $class);

        $output->writeln(sprintf('Command <info>%s</info> succesfully generated', $command));
    }

    /**
     * Gets the command generator.
     *
     * @return CommandGenerator
     */
    public function getGenerator()
    {
        if (null === $this->generator) {
            $this->generator = new CommandGenerator(__DIR__.'/../Resources/skeleton/command');
        }

        return $this->generator;
    }

    /**
     * Sets the command generator.
     *
     * @param CommandGenerator $generator command generator
     */
    public function setGenerator(CommandGenerator $generator)
    {
        $this->generator = $generator;
    }
}
