<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Sensio\Bundle\GeneratorBundle\Command\GenerateCommandCommand;

class GenerateCommandCommandTest extends GenerateCommandTest
{
    /**
     * @dataProvider getCommandData
     */
    public function testCommand($options, $expected)
    {
        list($command, $class) = $expected;

        $generator = $this->getGenerator();
        $generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->getBundle(), $command, $class)
        ;

        $tester = new CommandTester($this->getCommand($generator, ''));
        $tester->execute($options);
    }

    public function getCommandData()
    {
        return array(
            array(array('bundle' => 'AcmeTestBundle', 'command_name' => 'my:test-task'), array('my:test-task', 'MyTestTaskCommand')),
            array(array('bundle' => 'AcmeTestBundle', 'command_name' => 'my:test-task', '--class' => 'MyCustomClass'), array('my:test-task', 'MyCustomClassCommand')),
        );
    }

    protected function getCommand($generator, $input)
    {
        $command = new GenerateCommandCommand();
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setGenerator($generator);

        return $command;
    }

    protected function getGenerator()
    {
        // get a noop generator
        return $this
            ->getMockBuilder('Sensio\Bundle\GeneratorBundle\Generator\CommandGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generate'))
            ->getMock()
        ;
    }
}
