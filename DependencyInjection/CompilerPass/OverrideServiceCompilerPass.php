<?php

namespace Vlabs\MediaBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('vich_uploader.storage.flysystem');
        $definition->setClass('Vlabs\MediaBundle\Storage\Storage');
        $definition->addArgument(new Reference('oneup_flysystem.mount_manager'));
    }

} 