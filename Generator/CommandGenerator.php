<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a command class inside a bundle.
 */
class CommandGenerator extends Generator
{
    /**
     * @var string
     */
    private $skeletonDir;

    /**
     * @param string $skeletonDir folder containing command class templates
     */
    public function __construct($skeletonDir)
    {
        $this->skeletonDir = $skeletonDir;
    }

    /**
     * Generates a command class file.
     *
     * @param BundleInterface $bundle  bundle where the command class file is generated
     * @param string          $command command name
     * @param string          $class   command class name
     */
    public function generate(BundleInterface $bundle, $command, $class)
    {
        $classFile = $bundle->getPath().'/Command/'.$class.'.php';
        if (file_exists($classFile)) {
            throw new \RuntimeException(sprintf('Unable to generate the command as the file "%s" already exists.', realpath($classFile)));
        }

        $parameters = array(
            'namespace' => $bundle->getNamespace(),
            'class'     => $class,
            'command'   => $command,
        );

        $this->renderFile($this->skeletonDir, 'Command.php', $classFile, $parameters);
    }
}
