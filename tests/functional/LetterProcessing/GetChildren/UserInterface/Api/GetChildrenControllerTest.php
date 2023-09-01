<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\GetChildren\UserInterface\Api;

use Fixtures\Factory\LetterProcessing\ChildFactory;
use Fixtures\Factory\LetterProcessing\LetterFactory;
use Symfony\Component\HttpFoundation\Request;
use Tests\functional\AbstractFunctionalTestCase;

class GetChildrenControllerTest extends AbstractFunctionalTestCase
{
    public function testGetChildrenSuccessfully(): void
    {
        // Given some children exists
        $numberOfChildren = 3;
        ChildFactory::createMany($numberOfChildren);
        LetterFactory::createMany($numberOfChildren * 2, function () {
            return ['child' => ChildFactory::random()];
        });

        // When I request the list of children
        $this->client->request(
            method: Request::METHOD_GET,
            uri: '/children',
            parameters: ['v' => 100]
        );

        /** @var array<string, mixed> $responseContent */
        $responseContent = json_decode(
            json: $this->client->getResponse()->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertResponseStatusCodeSame(200);

        // I should get the number of existing children
        self::assertCount($numberOfChildren, $responseContent);

        // And I should get the following data
        $expectedKeys = [
            'id',
            'firstName',
            'lastName',
            'address',
            'letters',
        ];

        foreach ($expectedKeys as $expectedKey) {
            self::assertArrayHasKey(
                $expectedKey,
                $responseContent[0],
            );
        }
    }
}
