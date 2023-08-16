<?php

declare(strict_types=1);

namespace Tests\functional\ChildWatching\CreateChild\Command;

use App\LetterProcessing\Shared\Domain\Child\NewChildSentLetter;
use Fixtures\Factory\ChildWatching\ChildFactory;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractEventConsumerFunctionalTestCase;

class CreateChildHandlerTest extends AbstractEventConsumerFunctionalTestCase
{
    public function testCreateChildSuccessfully(): void
    {
        // Given no child has been created
        self::assertEquals(0, ChildFactory::repository()->count());

        $this->globalQueueShouldBeEmpty();
        $this->receivedEventStoreShouldBeEmpty();

        // When the event notifying that a new child was created, since he sent a letter, is dispatched
        $newChildSentLetter = new NewChildSentLetter(
            childId: (string) new Ulid(),
            firstName: 'toto',
            lastName: 'Tata',
            streetNumber: 45,
            streetName: 'Coconut street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        );
        $this->eventBus->dispatch($newChildSentLetter);

        $this->globalQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['global_queue'],
            '--limit' => 1,
        ]);

        // Then, the event should be consumed successfully
        $this->commandTester->assertCommandIsSuccessful();

        $this->globalQueueShouldBeEmpty();
        $this->receivedEventStoreShouldContainThisNumberOfEvent(1);

        // And the id of the last received event should be the same as the dispatched event
        $lastReceivedEvent = $this->getLastReceivedEvent();
        self::assertEquals($newChildSentLetter->getId(), $lastReceivedEvent->getId());

        // And a child should have created
        self::assertEquals(1, ChildFactory::repository()->count());
    }

    public function testCreateChildFailsIfChildWasAlreadyCreated(): void
    {
        // Given a child has been created
        $child = ChildFactory::createOne();
        self::assertEquals(1, ChildFactory::repository()->count());

        // When the event notifying that a new child was created since he sent a letter is dispatched
        // And the event contains the previously created child id
        $newChildSentLetter = new NewChildSentLetter(
            childId: (string) $child->getId(),
            firstName: 'toto',
            lastName: 'Tata',
            streetNumber: 45,
            streetName: 'Coconut street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        );
        $this->eventBus->dispatch($newChildSentLetter);

        $this->globalQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['global_queue'],
            '--limit' => 1,
        ]);

        // Then no child should be created
        self::assertEquals(1, ChildFactory::repository()->count());

        // And the id of the last received event should be the same as the dispatched event
        $lastReceivedEvent = $this->getLastReceivedEvent();
        self::assertEquals($newChildSentLetter->getId(), $lastReceivedEvent->getId());
        self::assertNotNull($lastReceivedEvent->getExceptionsLog());
    }
}
