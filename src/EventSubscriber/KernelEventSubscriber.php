<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventSubscriber implements EventSubscriberInterface {

    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @return \array[][]
     */
    public static function getSubscribedEvents(): array {
        return[
          KernelEvents::REQUEST => [
              ["displayKernelRequestTriggered", 0]
          ],
            KernelEvents::EXCEPTION => [
                ["logKernelExceptionTriggered", 255],
                ["displayKernelExceptionTriggered", 1],
            ]
        ];
    }

    public function displayKernelRequestTriggered(RequestEvent $event) {
        if ($event->getRequest()->getMethod() !== "POST") {
            $response = new Response(null, 403);
            $event->setResponse($response);
        }
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function displayKernelExceptionTriggered(ExceptionEvent $event) {
        if ($event->getRequest()->getMethod() !== "POST") {
            $response = new Response(null, 404);
            $event->setResponse($response);
        }
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function logKernelExceptionTriggered(ExceptionEvent $event) {
        $message = $event->getThrowable()->getMessage();
        file_put_contents(__DIR__ . "/../../var/log/test.log", "$message\n", FILE_APPEND);
        $this->logger->error("Exception {kernel.exception::logKernelExceptionTriggered()}", ['Request from' => $message]);
    }
}