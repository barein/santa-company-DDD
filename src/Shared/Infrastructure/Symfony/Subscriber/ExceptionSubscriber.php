<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Subscriber;

use App\Shared\Domain\Exception\AbstractBaseException;
use App\Shared\Domain\Exception\HttpStatusCode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            /** @var \Throwable $exception */
            $exception = $exception->getPrevious();
        }

        $event->allowCustomResponseCode();
        $event->setResponse(
            new JsonResponse(
                data: [
                    'code' => $this->getExceptionCodeFor($exception),
                    'message' => $exception->getMessage(),
                    'metadatas' => $this->getMetadatas($exception),
                ],
                status: $this->getStatusCodeFor($exception)
            )
        );
    }

    private function getExceptionCodeFor(\Throwable $exception): string
    {
        if ($exception instanceof AbstractBaseException) {
            return $exception->getCodeError();
        }

        return $this->fromPascalCaseToUpperCaseSnakeCase($this->extractClassName($exception));
    }

    /**
     * @return array<mixed>
     */
    private function getMetadatas(\Throwable $exception): array
    {
        $metadatas = [];

        if ($exception instanceof AbstractBaseException) {
            $metadatas = $exception->getMetadatas();
        }

        return $metadatas;
    }

    public static function getStatusCodeFor(\Throwable $exception): int
    {
        if ($exception instanceof AbstractBaseException) {
            return $exception->getHttpStatusCode()->value;
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR->value;
    }

    private function fromPascalCaseToUpperCaseSnakeCase(string $text): string
    {
        return ctype_upper($text) ? $text : strtoupper((string) preg_replace('#([a-z])([A-Z])#', '$1_$2', $text));
    }

    private function extractClassName(object $object): string
    {
        $reflect = new \ReflectionClass($object);

        return $reflect->getShortName();
    }
}
