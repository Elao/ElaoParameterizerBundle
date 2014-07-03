<?php

/**
 * This file is part of the ElaoParameterizer bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\ParameterizerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * This is the class that loads and manages your bundle configuration
 */
class ElaoParameterizerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Get parameterizer
        $parameterizerDefinitionId = 'elao_parameterizer';
        $parameterizerDefinition = $container->getDefinition($parameterizerDefinitionId);

        // Parameterizer name
        if ($config['name']) {
            $container->setParameter('elao_parameterizer.key', $config['name']);
        }

        // Parameterizer patterns
        foreach ($config['patterns'] as $patternName => $patternConfig) {

            $expression = 'service("elao_parameterizer.factory").createPattern("' . $patternName . '"';

            if (isset($patternConfig['options'])) {
                $expression .= ', ' . json_encode($patternConfig['options']);
            }

            $expression .= ')';

            foreach ($patternConfig['parameters'] as $parameterName => $parameterConfig) {
                $expression .= '.create("' . $parameterName . '"';
                switch (true) {
                    case is_null($parameterConfig['value']):
                        $expression .= ', null';
                        break;
                    case is_bool($parameterConfig['value']):
                        $expression .= ', ' . ($parameterConfig['value'] ? 'true' : 'false');
                        break;
                    case is_string($parameterConfig['value']):
                        $expression .= ', "' . $parameterConfig['value'] . '"';
                        break;
                    case is_array($parameterConfig['value']):
                        $expression .= ', ' . json_encode($parameterConfig['value']);
                        break;
                    default:
                        $expression .= ', ' . $parameterConfig['value'];
                        break;
                }

                $expression .= ', ' . json_encode($parameterConfig['options']);
                $expression .= ')';
            }

            $parameterizerDefinition
                ->addMethodCall(
                    'add',
                    array(new Expression($expression))
                );
        }
    }
}
