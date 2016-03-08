<?php

namespace Vlabs\MediaBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueuingCompilerPass implements CompilerPassInterface
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
        if (!$container->has('vlabs_media.queuing_chain')) {
            return;
        }

        $definition = $container->findDefinition(
            'vlabs_media.queuing_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'vlabs_media.queuing'
        );

        foreach ($taggedServices as $id => $tags) {
            foreach($tags as $attributes) {
                $definition->addMethodCall(
                    'addQueuing',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }

} 