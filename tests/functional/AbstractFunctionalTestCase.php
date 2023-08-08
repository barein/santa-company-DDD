<?php

declare(strict_types=1);

namespace Tests\functional;

use App\Shared\Domain\Event\EventsToDispatchTrackerInterface;
use App\Shared\Domain\Event\StoredEvent;
use App\Shared\Infrastructure\Event\DoctrineEventStore;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class AbstractFunctionalTestCase extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    protected KernelBrowser $client;

    protected DoctrineEventStore $doctrineEventStore;

    private InMemoryTransport $globalQueue;

    private InMemoryTransport $letterProcessingContextQueue;

    private int $initialNumberOfEventStored;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->doctrineEventStore = static::getContainer()->get(DoctrineEventStore::class);
        $this->globalQueue = static::getContainer()->get('messenger.transport.global_queue');
        $this->letterProcessingContextQueue = static::getContainer()->get('messenger.transport.letter_processing_queue');
        unset($this->initialNumberOfEventStored);
    }

    protected function eventStoreShouldContainThisNumberOfEvent(int $numberOfEvent): void
    {
        self::assertEquals($numberOfEvent, $this->doctrineEventStore->count([]));
    }

    protected function eventStoreShouldBeEmpty(): void
    {
        $this->eventStoreShouldContainThisNumberOfEvent(0);
    }

    protected function getLastStoredEvent(): StoredEvent
    {
        /** @var StoredEvent[] $storedEvents */
        $storedEvents = $this->doctrineEventStore->findAll();

        if (\count($storedEvents) === 0) {
            throw new \RuntimeException('Impossible to get last event stored if event store is empty');
        }

        return $storedEvents[array_key_last($storedEvents)];
    }

    protected function givenAnInitialNumberOfStoredEvent(): void
    {
        $this->initialNumberOfEventStored = $this->doctrineEventStore->count([]);
    }

    protected function numberOfEventStoredShouldHaveIncreasedBy(int $numberOfEventIncremented): void
    {
        self::assertEquals($this->initialNumberOfEventStored + $numberOfEventIncremented, $this->doctrineEventStore->count([]));
    }

    protected function numberOfEventStoredShouldNotHaveChanged(): void
    {
        $this->numberOfEventStoredShouldHaveIncreasedBy(0);
    }

    protected function globalQueueShouldContainThisNumberOfMessage(int $numberOfMessage): void
    {
        self::assertCount($numberOfMessage, $this->globalQueue->get());
    }

    protected function globalQueueShouldBeEmpty(): void
    {
        $this->globalQueueShouldContainThisNumberOfMessage(0);
    }

    protected function letterProcessingContextQueueShouldContainThisNumberOfMessage(int $numberOfMessage): void
    {
        self::assertCount($numberOfMessage, $this->letterProcessingContextQueue->get());
    }

    protected function letterProcessingContextQueueShouldBeEmpty(): void
    {
        $this->letterProcessingContextQueueShouldContainThisNumberOfMessage(0);
    }

    /**
     * @param class-string $class
     *
     * @throws \Exception
     */
    protected function getEntityPersisterForClass(string $class): EntityPersister
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(ManagerRegistry::class)->getManagerForClass($class);
        /** @var EntityPersister $entityPersister */
        $entityPersister = $entityManager->getUnitOfWork()->getEntityPersister($class);

        return $entityPersister;
    }

    /**
     * While arranging data (creating entities) domain events can be stored and marked as "to dispatch"
     * Subsequent calls using the client will trigger use cases which will dispatch these events
     * making assertions on queues content more complicated
     */
    public function emptyEventsToDispatchList(): void
    {
        static::getContainer()->get(EventsToDispatchTrackerInterface::class)->getEventsToDispatch();
    }
}
