<?php

/**
 * This file is part of the ElaoParameterizer bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\ParameterizerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elao_parameterizer');

        $rootNode
            ->children()
                ->scalarNode('name')
                    ->defaultNull()
                ->end()
                ->arrayNode('patterns')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('options')
                                ->children()
                                    ->scalarNode('label')
                                        ->defaultNull()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('parameters')
                                ->isRequired()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->beforeNormalization()
                                        ->always(function ($value) {
                                            if (!is_array($value)) {
                                                return array('value' => $value);
                                            }
                                            return $value;
                                        })
                                    ->end()
                                    ->children()
                                        ->scalarNode('value')
                                            ->isRequired()
                                        ->end()
                                        ->arrayNode('options')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('label')->end()
                                                ->scalarNode('min')->end()
                                                ->scalarNode('max')->end()
                                                ->scalarNode('step')->end()
                                                ->arrayNode('choices')
                                                    ->prototype('scalar')
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
