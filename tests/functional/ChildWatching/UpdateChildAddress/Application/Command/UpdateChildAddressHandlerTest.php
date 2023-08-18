<?php

declare(strict_types=1);

namespace Tests\functional\ChildWatching\UpdateChildAddress\Application\Command;

use App\LetterProcessing\Shared\Domain\Child\ChildMoved;
use App\Shared\Domain\Address;
use Fixtures\Factory\ChildWatching\ChildFactory;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractEventConsumerFunctionalTestCase;

class UpdateChildAddressHandlerTest extends AbstractEventConsumerFunctionalTestCase
{
    public function testUpdateChildAddressSuccessfully(): void
    {
        // Given a child exist
        $child = ChildFactory::createOne();

        $this->globalQueueShouldBeEmpty();
        $this->receivedEventStoreShouldBeEmpty();

        $newAddress = Address::from(
            number: 45,
            street: 'Coconut Street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        );

        // When the event notifying the child moved is dispatched
        $childMoved = new ChildMoved(
            childId: (string) $child->getId(),
            streetNumber: $newAddress->getNumber(),
            streetName: $newAddress->getStreet(),
            city: $newAddress->getCity(),
            zipCode: $newAddress->getZipCode(),
            isoCountryCode: $newAddress->getIsoCountryCode()->getValue(),
        );
        $this->eventBus->dispatch($childMoved);

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

        // And the child should have this new address
        $child->getAddress()->equal($newAddress);
    }

    public function testUpdateChildAddressFailsIfChildDoesNotExist(): void
    {
        // Given a child exist
        ChildFactory::createOne();

        $this->globalQueueShouldBeEmpty();
        $this->receivedEventStoreShouldBeEmpty();

        $newAddress = Address::from(
            number: 45,
            street: 'Coconut Street',
            city: 'San Francisco',
            zipCode: 55555,
            isoCountryCode: 'USA',
        );

        // When the event notifying the child moved is dispatched, but it mentions a random child id
        $childMoved = new ChildMoved(
            childId: (string) new Ulid(),
            streetNumber: $newAddress->getNumber(),
            streetName: $newAddress->getStreet(),
            city: $newAddress->getCity(),
            zipCode: $newAddress->getZipCode(),
            isoCountryCode: $newAddress->getIsoCountryCode()->getValue(),
        );
        $this->eventBus->dispatch($childMoved);

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

        // And the last received event should have an exception log
        $lastReceivedEvent = $this->getLastReceivedEvent();
        self::assertNotNull($lastReceivedEvent->getExceptionsLog());
    }
}
