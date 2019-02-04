<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ContentSecurityPolicySubscriber implements EventSubscriberInterface
{
    private $tokenGenerator;
    private $token;

    public function __construct(ContentSecurityPolicyTokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        $this->token = $this->tokenGenerator->generateToken();

        $event->getRequest()->attributes->set('csp_token', $this->token);
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $policies['default-src'] = '\'self\'';
        $policies['font-src'] = '\'self\' https://netdna.bootstrapcdn.com';
        $policies['img-src'] = '\'self\' https://maps.googleapis.com';
        $policies['frame-src'] = 'https://www.google.com';
        $policies['script-src'] = sprintf('\'self\' \'nonce-%s\' https://www.google-analytics.com https://ajax.googleapis.com https://netdna.bootstrapcdn.com https://oss.maxcdn.com', $this->token);
        $policies['style-src'] = '\'self\' https://netdna.bootstrapcdn.com';

        $policies = array_map(function ($value, $directive) {
            return sprintf('%s %s', $directive, $value);
        }, $policies, array_keys($policies));

        $response = $event->getResponse();
        $response->headers->set('Content-Security-Policy', $policies, true);
        $response->headers->set('Content-Security-Policy-Report-Only ', 'policy', true);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
