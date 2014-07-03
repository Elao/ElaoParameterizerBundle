<?php

/**
 * This file is part of the ElaoParameterizer bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\ParameterizerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Elao\Bundle\ParameterizerBundle\DependencyInjection\Compiler;

/**
 * Elao parameterizer bundle
 */
class ElaoParameterizerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(
                new Compiler\PatternPass()
            )
            ->addCompilerPass(
                new Compiler\ParameterizablePass()
            );
    }
}
