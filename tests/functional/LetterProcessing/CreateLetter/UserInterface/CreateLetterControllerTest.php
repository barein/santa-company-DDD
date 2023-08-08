<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\CreateLetter\UserInterface;

use App\LetterProcessing\Shared\Domain\Letter;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Fixtures\Factory\LetterProcessing\LetterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractFunctionalTestCase;

class CreateLetterControllerTest extends AbstractFunctionalTestCase
{
    public function testCreateLetterSuccessfully(): void
    {
        // Given a child exists
        $child = ChildFactory::createOne();
        $this->emptyEventsToDispatchList();

        // And this child never sent a letter
        $lettersPersister = $this->getEntityPersisterForClass(Letter::class);
        $lettersFromChild = $lettersPersister->loadAll(['child' => $child->object()]);
        self::assertCount(0, $lettersFromChild);

        // And given an initial number of event stored
        $doctrineEventStore = $this->getDoctrineEventStore();
        $initialNumberOfEvent = $doctrineEventStore->count([]);

        // When I create a child with the following infos
        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $child->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 201
        self::assertResponseStatusCodeSame(201);

        // And a letter should be linked to this child
        $lettersFromChild = $lettersPersister->loadAll(['child' => $child->object()]);
        self::assertCount(1, $lettersFromChild);

        // And no event should have been stored nor dispatched
        self::assertEquals($initialNumberOfEvent, $doctrineEventStore->count([]));
        self::assertCount(0, $this->getGlobalQueue()->get());
    }

    /**
     * @dataProvider unprocessablePayloadProvider
     */
    public function testCreateLetterFailsBecauseOfUnprocessablePayload(array $payload): void
    {
        // Given a child exists
        $child = ChildFactory::createOne();
        $this->emptyEventsToDispatchList();

        // When I create a child with an unprocessable payload
        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $child->getId()),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 422
        self::assertResponseStatusCodeSame(422);
    }

    public function unprocessablePayloadProvider(): iterable
    {
        yield 'receivingDate bad format' => [[
            'receivingDate' => '2023/06/12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ]];

        yield 'senderStreetNumber is 0' => [[
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 0,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ]];

        yield 'senderStreet is empty' => [[
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => '',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ]];

        yield 'senderCity is empty' => [[
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => '',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ]];

        yield 'senderIsoCountryCode is invalid' => [[
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'KKKKKKKK',
        ]];
    }

    public function testCreateLetterFailsBecauseChildDoesNotExists(): void
    {
        // When I try to create a Child with a valid payload but a random child id
        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', new Ulid()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a 404 response status code
        self::assertResponseStatusCodeSame(404);
    }

    public function testCreateLetterFailsBecauseChildAlreadySentALetterThisYear(): void
    {
        // Given a child exist, and he sent a letter that was received on the 2023-05-10
        $child = ChildFactory::createOne();
        LetterFactory::createOne([
            'child' => $child,
            'receivingDate' => \DateTimeImmutable::createFromFormat(Letter::RECEIVING_DATE_FORMAT, '2023-05-10'),
        ]);
        $this->emptyEventsToDispatchList();

        // When I create a letter with a receiving date of 2023-06-12
        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $child->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a 500 response status code
        self::assertResponseStatusCodeSame(500);

        // And the error message should mention that a letter was already sent the same year
        self::assertStringContainsString(
            'has already sent a letter this same year',
            $this->client->getResponse()->getContent(),
        );
    }
}
