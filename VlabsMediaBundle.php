<?php

namespace Vlabs\MediaBundle;

use Vlabs\MediaBundle\DependencyInjection\CompilerPass\OverrideServiceCompilerPass;
use Vlabs\MediaBundle\DependencyInjection\CompilerPass\QueuingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VlabsMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new QueuingCompilerPass());
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }

}
