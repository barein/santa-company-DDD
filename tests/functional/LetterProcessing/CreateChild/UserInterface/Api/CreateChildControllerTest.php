<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\CreateChild\UserInterface\Api;

use App\LetterProcessing\Shared\Domain\Child\Child;
use App\LetterProcessing\Shared\Domain\Child\NewChildSentLetter;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\functional\AbstractFunctionalTestCase;

class CreateChildControllerTest extends AbstractFunctionalTestCase
{
    public function testCreateChildSuccessfully(): void
    {
        // Given that no child named Mark Hamill living in London exist
        $child = ChildFactory::repository()->findOneBy(['firstName' => 'Mark', 'lastName' => 'Hamill', 'address.city' => 'London']);
        self::assertNull($child);

        $this->eventStoreShouldBeEmpty();
        $this->globalQueueShouldBeEmpty();

        // When I create a child with the following infos
        $requestContent = [
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 45,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: '/children',
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 201
        self::assertResponseStatusCodeSame(201);

        // And a child named Mark Hamill living in London should be created
        $child = ChildFactory::repository()->findOneBy(['firstName' => 'Mark', 'lastName' => 'Hamill', 'address.city' => 'London']);
        self::assertInstanceOf(Child::class, $child->object());

        // And an event should be stored and marked as dispatched
        $this->eventStoreShouldContainThisNumberOfEvent(1);
        $lastStoredEvent = $this->getLastStoredEvent();
        self::assertEquals(NewChildSentLetter::getName(), $lastStoredEvent->getName());
        self::assertTrue($lastStoredEvent->hasBeenDispatched());

        // And it should be queued in global queue
        $this->globalQueueShouldContainThisNumberOfMessage(1);
    }

    /**
     * @dataProvider unprocessablePayloadProvider
     */
    public function testCreateChildFailsBecauseOfUnprocessablePayload(array $payload): void
    {
        // When I create a child with an unprocessable payload
        $this->client->request(
            method: Request::METHOD_POST,
            uri: '/children',
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        // Then I should get a 422 response status code
        self::assertResponseStatusCodeSame(422);

        $this->eventStoreShouldBeEmpty();
        $this->globalQueueShouldBeEmpty();
    }

    public function unprocessablePayloadProvider(): iterable
    {
        yield 'Empty firstName' => [[
            'firstName' => '',
            'lastName' => 'Hamill',
            'streetNumber' => 45,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ]];

        yield 'Empty lastName' => [[
            'firstName' => 'Mark',
            'lastName' => '',
            'streetNumber' => 45,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ]];

        yield 'Street number is 0' => [[
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 0,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ]];

        yield 'Street name is empty' => [[
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 42,
            'street' => '',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ]];

        yield 'City name is empty' => [[
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 42,
            'street' => 'Baker street',
            'city' => '',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ]];

        yield 'iso country code is invalid' => [[
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 42,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'PPPPP',
        ]];
    }
}
