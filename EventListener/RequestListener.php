<?php

/**
 * This file is part of the ElaoParameterizer bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\ParameterizerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Cookie;

use Elao\Parameterizer\Parameterizer;

/**
 * Request listener
 */
class RequestListener implements EventSubscriberInterface
{
    /**
     * Parameterizer
     *
     * @var Parameterizer
     */
    protected $parameterizer;

    /**
     * Constructor
     *
     * @param Parameterizer $parameterizer
     */
    public function __construct(Parameterizer $parameterizer)
    {
        // Parameterizer
        $this->parameterizer = $parameterizer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST  => array('onKernelRequest', 255),
            KernelEvents::RESPONSE => 'onKernelResponse'
        );
    }

    /**
     * On kernel request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        // Get request
        $request = $event->getRequest();

        $parameters = array();

        // Try with request cookies parameters...
        if ($request->cookies->has($this->parameterizer->getName())) {
            $parameters = array_merge(
                $parameters,
                json_decode(
                    $request->cookies->get($this->parameterizer->getName()),
                    true
                )
            );
        }

        // ... then with request query parameters
        if ($request->query->has($this->parameterizer->getName())) {
            $parameters = array_merge(
                $parameters,
                json_decode(
                    $request->get($this->parameterizer->getName()),
                    true
                )
            );
        }

        // Merge
        if ($parameters) {
            $this->parameterizer->mergeValues($parameters);
        }
    }

    /**
     * On kernel response
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // Get response
        $response = $event->getResponse();

        // Get parameters
        $parameters = $this->parameterizer->getValues();

        if ($parameters) {
            $response->headers->setCookie(
                new Cookie(
                    $this->parameterizer->getName(),
                    json_encode($parameters),
                    0,
                    '/',
                    null,
                    false,
                    false
                )
            );
        }
    }
}
