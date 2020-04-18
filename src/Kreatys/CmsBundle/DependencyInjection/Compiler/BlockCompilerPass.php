<?php

namespace Kreatys\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlockCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $manager = $container->getDefinition('kreatys_cms.manager.block');
        
        $taggedServices = $container->findTaggedServiceIds('kreatys_cms.block');        
        
        foreach ($taggedServices as $id => $tags) {            
            $manager->addMethodCall('add', array($id, new Reference($id), $tags[0]));
        }
    }
}
