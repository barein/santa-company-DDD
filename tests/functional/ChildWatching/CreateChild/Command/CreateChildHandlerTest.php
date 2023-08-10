<?php

declare(strict_types=1);

namespace Tests\functional\ChildWatching\CreateChild\Command;

use App\LetterProcessing\Shared\Domain\NewChildSentLetter;
use Fixtures\Factory\ChildWatching\ChildFactory;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractEventConsumerFunctionalTestCase;

class CreateChildHandlerTest extends AbstractEventConsumerFunctionalTestCase
{
    public function testCreateChildSuccessfully(): void
    {
        // Given no child has been created
        self::assertEquals(0, ChildFactory::repository()->count());

        // When the event notifying that a new child was created since he sent a letter is dispatched
        $this->eventBus->dispatch(new NewChildSentLetter(
            childId: (string) new Ulid(),
            firstName: 'toto',
            lastName: 'Tata',
            streetNumber: 45,
            streetName: 'Coconut street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        ));

        $this->globalQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['global_queue'],
            '--limit' => 1,
        ]);

        // Then, the event should be consumed successfully
        $this->commandTester->assertCommandIsSuccessful();

        $this->globalQueueShouldBeEmpty();

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
        $this->eventBus->dispatch(new NewChildSentLetter(
            childId: (string) $child->getId(),
            firstName: 'toto',
            lastName: 'Tata',
            streetNumber: 45,
            streetName: 'Coconut street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        ));

        $this->globalQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['global_queue'],
            '--limit' => 1,
        ]);

        // Then no child should be created
        self::assertEquals(1, ChildFactory::repository()->count());
    }
}
