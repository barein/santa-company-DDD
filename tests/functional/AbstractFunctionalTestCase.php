<?php

declare(strict_types=1);

namespace Tests\functional;

use App\Shared\Domain\Event\EventsToDispatchTrackerInterface;
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

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function getDoctrineEventStore(): DoctrineEventStore
    {
        /** @var DoctrineEventStore $doctrineEventStore */
        $doctrineEventStore = static::getContainer()->get(DoctrineEventStore::class);

        return $doctrineEventStore;
    }

    protected function getGlobalQueue(): InMemoryTransport
    {
        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()->get('messenger.transport.global_queue');

        return $transport;
    }

    protected function getLetterProcessingContextQueue(): InMemoryTransport
    {
        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()->get('messenger.transport.letter_processing_queue');

        return $transport;
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
