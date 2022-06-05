<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

final class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', -1],
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if ('json' !== $request->getContentType()) {
            return;
        }

        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationException) {
            return;
        }

        $payload = $this->serializer->serialize($exception->getViolations(), 'json', [
            'title' => $exception->getMessage(),
        ]);

        $event->setResponse(new JsonResponse($payload, Response::HTTP_BAD_REQUEST, ['Vary' => 'Accept'], true));
    }
}
