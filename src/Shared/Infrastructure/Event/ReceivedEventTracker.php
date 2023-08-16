<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Event\ReceivedEventStoreInterface;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Ulid;

readonly class ReceivedEventTracker implements MiddlewareInterface
{
    public function __construct(
        private ReceivedEventStoreInterface $receivedEventStore,
        #[Autowire(service: 'event.bus.messenger.handlers_locator')]
        private HandlersLocatorInterface $handlersLocator,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if ($message instanceof DomainEventInterface && $envelope->last(ReceivedStamp::class) !== null) {
            $domainEvent = $message;

            try {
                // Todo: improvement => get by id and receiver context ?
                $receivedEvent = $this->receivedEventStore->get(new Ulid($domainEvent->getId()));

                if ($receivedEvent->hasBeenHandledSuccessfully()) {
                    throw new UnrecoverableMessageHandlingException(sprintf(
                        'Event %s has already been handled successfully',
                        $domainEvent->getId(),
                    ));
                }
            } catch (NotFoundException) {
                $this->receivedEventStore->append($domainEvent, $this->getReceiverContexts($envelope));
                $this->receivedEventStore->store();
            }
        }

        try {
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $exception) {
            if ($message instanceof DomainEventInterface) {
                /** @var \Throwable $exception */
                $exception = $exception->getPrevious();
                $domainEvent = $message;
                $receivedEvent = $this->receivedEventStore->get(new Ulid($domainEvent->getId()));
                $receivedEvent->logException($exception);
                $this->receivedEventStore->store();
            }
        }

        if ($message instanceof DomainEventInterface && $envelope->last(HandledStamp::class) !== null) {
            $domainEvent = $message;
            $receivedEvent = $this->receivedEventStore->get(new Ulid($domainEvent->getId()));
            $receivedEvent->markAsHandledSuccessfully();
            $this->receivedEventStore->store();
        }

        return $envelope;
    }

    /**
     * @return array<string>
     */
    private function getReceiverContexts(Envelope $eventEnvelope): array
    {
        $handlerDescriptors = $this->handlersLocator->getHandlers($eventEnvelope);

        $contexts = [];
        foreach ($handlerDescriptors as $handlerDescriptor) {
            preg_match(
                '#App\\\(?<context>.+?)\\\#',
                $handlerDescriptor->getName(),
                $matches,
            );

            $contexts[] = (string) (new UnicodeString($matches['context']))->snake();
        }

        return array_unique($contexts);
    }
}
