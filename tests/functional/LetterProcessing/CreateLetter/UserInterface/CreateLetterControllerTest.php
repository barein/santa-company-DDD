<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\CreateLetter\UserInterface;

use App\LetterProcessing\Shared\Domain\Letter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Fixtures\Factory\LetterProcessing\LetterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractFunctionalTestCase;

class CreateLetterControllerTest extends AbstractFunctionalTestCase
{
    public function testCreateLetterSuccessfully(): void
    {
        $client = static::createClient();

        $childId = new Ulid();
        $child = ChildFactory::createOne(['id' => $childId]);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(ManagerRegistry::class)->getManagerForClass(Letter::class);
        $lettersFromChild = $entityManager->getUnitOfWork()->getEntityPersister(Letter::class)->loadAll(['child' => $child->object()]);
        self::assertCount(0, $lettersFromChild);

        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $childId),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        $lettersFromChild = $entityManager->getUnitOfWork()->getEntityPersister(Letter::class)->loadAll(['child' => $child->object()]);
        self::assertCount(1, $lettersFromChild);
    }

    /**
     * @dataProvider unprocessablePayloadProvider
     */
    public function testCreateLetterFailsBecauseOfUnprocessablePayload(array $payload): void
    {
        $client = static::createClient();

        $childId = new Ulid();
        ChildFactory::createOne(['id' => $childId]);

        $client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $childId),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

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
        $client = static::createClient();

        $childId = new Ulid();

        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $childId),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(404);
    }

    public function testCreateLetterFailsBecauseChildAlreadySentALetterThisYear(): void
    {
        $client = static::createClient();

        $childId = new Ulid();
        $child = ChildFactory::createOne(['id' => $childId]);
        LetterFactory::createOne([
            'child' => $child,
            'receivingDate' => \DateTimeImmutable::createFromFormat(Letter::RECEIVING_DATE_FORMAT, '2023-05-10'),
        ]);

        $requestContent = [
            'receivingDate' => '2023-06-12',
            'senderStreetNumber' => 45,
            'senderStreet' => 'Coconut avenue',
            'senderCity' => 'San Fransisco',
            'senderZipCode' => 12345,
            'senderIsoCountryCode' => 'USA',
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters', $childId),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(500);
        self::assertStringContainsString(
            'has already sent a letter this same year',
            $client->getResponse()->getContent(),
        );
    }
}
