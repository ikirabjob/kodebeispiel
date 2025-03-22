<?php

namespace App\Infrastructure\Shared\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RemoveBuiltinProvidersAndPersistersPass implements CompilerPassInterface
{
    private const TAGS_TO_CLEAR = [
        'api_platform.collection_data_provider',
        'api_platform.subresource_data_provider',
        'api_platform.item_data_provider',
        'api_platform.data_persister',
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::TAGS_TO_CLEAR as $tag) {
            foreach ($container->findTaggedServiceIds($tag) as $id => $_) {
                $definition = $container->findDefinition($id);
                if (str_contains($definition->getClass() ?? '', 'App\\')) {
                    continue;
                }

                $definition->clearTag($tag);
            }
        }
    }
}
