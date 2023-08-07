<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\CreateChild\UserInterface\Api;

use App\LetterProcessing\Shared\Domain\Child;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\functional\AbstractFunctionalTestCase;

class CreateChildControllerTest extends AbstractFunctionalTestCase
{
    public function testCreateChildSuccessfully(): void
    {
        $client = static::createClient();

        $child = ChildFactory::repository()->findOneBy(['firstName' => 'Mark', 'lastName' => 'Hamill', 'address.city' => 'London']);
        self::assertNull($child);

        $requestContent = [
            'firstName' => 'Mark',
            'lastName' => 'Hamill',
            'streetNumber' => 45,
            'street' => 'Baker street',
            'city' => 'London',
            'zipCode' => 12345,
            'isoCountryCode' => 'GBR',
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: '/children',
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        $child = ChildFactory::repository()->findOneBy(['firstName' => 'Mark', 'lastName' => 'Hamill', 'address.city' => 'London']);
        self::assertInstanceOf(Child::class, $child->object());
    }

    /**
     * @dataProvider unprocessablePayloadProvider
     */
    public function testCreateChildFailsBecauseOfUnprocessablePayload(array $payload): void
    {
        $client = static::createClient();
        $client->request(
            method: Request::METHOD_POST,
            uri: '/children',
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(422);
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
