<?php

namespace Vlabs\MediaBundle;

use Vlabs\MediaBundle\DependencyInjection\CompilerPass\OverrideServiceCompilerPass;
use Vlabs\MediaBundle\DependencyInjection\CompilerPass\QueuingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VlabsMediaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container) :void
    {
        parent::build($container);

        $container->addCompilerPass(new QueuingCompilerPass());
    }

}
