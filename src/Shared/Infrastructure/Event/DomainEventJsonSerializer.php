<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Exception\UnexpectedException;
use Ds\Vector;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class DomainEventJsonSerializer implements SerializerInterface
{
    private const STAMP_HEADER_PREFIX = 'X-Message-Stamp-';

    public function __construct(
        private SymfonySerializerInterface $serializer,
        private string $projectDir,
    ) {
    }

    /**
     * @param array<mixed> $encodedEnvelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body']) || empty($encodedEnvelope['headers'])) {
            throw new MessageDecodingFailedException(
                'Encoded envelope should have at least a "body" and some "headers".'
            );
        }

        if (empty($encodedEnvelope['headers']['name'])) {
            throw new MessageDecodingFailedException('Encoded envelope does not have a "name" header.');
        }

        if (empty($encodedEnvelope['headers']['version'])) {
            throw new MessageDecodingFailedException('Encoded envelope does not have a "version" header.');
        }

        if (empty($encodedEnvelope['headers']['context'])) {
            throw new MessageDecodingFailedException('Encoded envelope does not have a "context" header.');
        }

        $stamps = $this->decodeStamps($encodedEnvelope);
        $busNameStampExist = (new Vector($stamps))
            ->map(fn (StampInterface $stamp) => $stamp instanceof BusNameStamp)
            ->contains(true);

        if ($busNameStampExist === false) {
            $stamps[] = new BusNameStamp('event.bus');
        }

        try {
            $eventClass = $this->findDomainEventClassFrom(
                (string) $encodedEnvelope['headers']['name'],
                (int) $encodedEnvelope['headers']['version'],
                (string) $encodedEnvelope['headers']['context'],
            );
            /** @var object $message */
            $message = $this->serializer->deserialize($encodedEnvelope['body'], $eventClass, 'json');
        } catch (\Throwable $e) {
            // Message is discarded from the queue, and when restarting the worker it will consume the next message
            throw new MessageDecodingFailedException('Could not decode message: '.$e->getMessage(), $e->getCode(), $e);
        }

        return new Envelope($message, $stamps);
    }

    /**
     * @throws UnexpectedException
     *
     * @return array<mixed>
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if (!$message instanceof DomainEventInterface) {
            throw new UnexpectedException(sprintf(
                'Message %s should be an instance of %s to be dispatched in json',
                \get_class($message),
                DomainEventInterface::class,
            ));
        }

        $envelopeWithOnlySendableStamps = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        $headers = [
            'name' => $message->getName(),
            'version' => $message->getVersion(),
            'context' => $message->getContext(),
            'Content-Type' => 'application/json',
            ] + $this->encodeStamps($envelopeWithOnlySendableStamps);

        return [
            'body' => $this->serializer->serialize($message, 'json'),
            'headers' => $headers,
        ];
    }

    /**
     * @see Symfony\Component\Messenger\Transport\Serialization\Serializer
     *
     * @param array<string, array<string, string>> $encodedEnvelope
     *
     * @return array<StampInterface>
     */
    private function decodeStamps(array $encodedEnvelope): array
    {
        /** @var array<StampInterface> $stamps */
        $stamps = [];
        foreach ($encodedEnvelope['headers'] as $name => $value) {
            if (!str_starts_with($name, self::STAMP_HEADER_PREFIX)) {
                continue;
            }

            try {
                $stamps[] = $this->serializer->deserialize($value, substr($name, \strlen(self::STAMP_HEADER_PREFIX)).'[]', 'json');
            } catch (\Throwable $e) {
                throw new MessageDecodingFailedException('Could not decode stamp: '.$e->getMessage(), $e->getCode(), $e);
            }
        }
        if ($stamps) {
            $stamps = array_merge(...$stamps);
        }

        return $stamps;
    }

    /**
     * @see Symfony\Component\Messenger\Transport\Serialization\Serializer
     *
     * @return array<string, string>
     */
    private function encodeStamps(Envelope $envelope): array
    {
        if (!$allStamps = $envelope->all()) {
            return [];
        }

        $headers = [];
        foreach ($allStamps as $class => $stamps) {
            $headers[self::STAMP_HEADER_PREFIX.$class] = $this->serializer->serialize($stamps, 'json');
        }

        return $headers;
    }

    private function findDomainEventClassFrom(string $name, int $version, string $context): string
    {
        $domainFiles = (new Finder())->files()->in(sprintf('%s/src/*/Shared/Domain', $this->projectDir));

        /** @var array<string> $domainClasses */
        $domainClasses = array_map(
            fn (SplFileInfo $fileInfo) => preg_replace(['#.*/src#', '#/#', '#\.php#'], ['App', '\\', ''], $fileInfo->getRealPath()),
            iterator_to_array($domainFiles),
        );
        $domainEventClasses = array_filter(
            $domainClasses,
            fn (string $domainClass) => \in_array(DomainEventInterface::class, class_implements($domainClass)) // @phpstan-ignore-line
        );

        foreach ($domainEventClasses as $domainEventClass) {
            if (
                $domainEventClass::getName() !== $name ||
                $domainEventClass::getVersion() !== $version ||
                $domainEventClass::getContext() !== $context
            ) {
                continue;
            }

            return $domainEventClass;
        }

        throw new MessageDecodingFailedException(sprintf(
            "No event could match message with name '%s', version %d and context '%s'",
            $name,
            $version,
            $context,
        ));
    }
}
