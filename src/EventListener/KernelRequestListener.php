<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelRequestListener {

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event) {
        if ($event->getRequest()->getMethod() !== "POST") {
            $response = new Response();
            //$response->setContent("<h1>Type de requête non autorisée par le kernel </h1>");
            $event->setResponse(new Response(null, 403));
        }
    }
}