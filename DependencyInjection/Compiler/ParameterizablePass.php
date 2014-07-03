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
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Parameterizable pass
 */
class ParameterizablePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $parameterizableTag = 'elao_parameterizer.parameterizable';

        // Get tagged services
        $taggedServices = $container->findTaggedServiceIds($parameterizableTag);

        foreach ($taggedServices as $definitionId => $attributes) {
            $definition = $container->getDefinition($definitionId);

            foreach ($attributes as $attribute) {

                if (!isset($attribute['pattern-name'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "pattern-name" attribute on "%s" tags.', $definitionId, $parameterizableTag));
                }

                //$patternParametersDefinitionId = 'elao_parameterizer.patterns.' . $attribute['pattern-id'] . '.parameters';

                $definition->addMethodCall(
                    'setParameters',
                    array(
                        new Expression('service("elao_parameterizer").get("' . $attribute['pattern-name'] . '")')
                    )
                );
            }
        }
    }
}
