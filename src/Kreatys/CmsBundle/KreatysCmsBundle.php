<?php

namespace Kreatys\CmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kreatys\CmsBundle\DependencyInjection\Compiler\BlockCompilerPass;

class KreatysCmsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new BlockCompilerPass());
    }

}
