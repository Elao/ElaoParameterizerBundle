<?php

/**
 * This file is part of the ElaoParameterizer bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\ParameterizerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Pattern pass
 */
class PatternPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Get parameterizer
        $parameterizerDefinitionId = 'elao_parameterizer';
        $parameterizerDefinition = $container->getDefinition($parameterizerDefinitionId);

        $patternTag = 'elao_parameterizer.pattern';

        // Get tagged services
        $taggedServices = $container->findTaggedServiceIds($patternTag);

        foreach ($taggedServices as $definitionId => $attributes) {

            foreach ($attributes as $attribute) {

                $parameterizerDefinition
                    ->addMethodCall(
                        'add',
                        array(
                            new Reference($definitionId)
                        )
                    );
            }
        }
    }
}
