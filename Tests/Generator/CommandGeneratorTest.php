<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Tests\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\CommandGenerator;

class CommandGeneratorTest extends GeneratorTest
{
    public function testGenerate()
    {
        $this->getGenerator()->generate($this->getBundle(), 'foo:task', 'FooTaskCommand');

        $files = array(
            'Command/FooTaskCommand.php',
        );
        foreach ($files as $file) {
            $this->assertTrue(file_exists($this->tmpDir.'/'.$file), sprintf('%s has been generated', $file));
        }

        $content = file_get_contents($this->tmpDir.'/Command/FooTaskCommand.php');
        $strings = array(
            'namespace Foo\BarBundle\Command;',
            'class FooTaskCommand',
            'setName(\'foo:task\')',
        );
        foreach ($strings as $string) {
            $this->assertContains($string, $content);
        }
    }

    protected function getGenerator()
    {
        return new CommandGenerator(__DIR__.'/../../Resources/skeleton/command');
    }

    protected function getBundle()
    {
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())->method('getPath')->will($this->returnValue($this->tmpDir));
        $bundle->expects($this->any())->method('getName')->will($this->returnValue('FooBarBundle'));
        $bundle->expects($this->any())->method('getNamespace')->will($this->returnValue('Foo\BarBundle'));

        return $bundle;
    }
}
