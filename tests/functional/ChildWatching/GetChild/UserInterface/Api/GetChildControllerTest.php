<?php

declare(strict_types=1);

namespace Tests\functional\ChildWatching\GetChild\UserInterface\Api;

use Fixtures\Factory\ChildWatching\ActionFactory;
use Fixtures\Factory\ChildWatching\ChildFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractFunctionalTestCase;

class GetChildControllerTest extends AbstractFunctionalTestCase
{
    public function testGetChildSuccessfully(): void
    {
        // Given a child exist and has some reported actions
        $child = ChildFactory::createOne();
        ActionFactory::createMany(2, ['childId' => $child->getId()]);

        //  When I get a child
        $this->client->request(
            method: Request::METHOD_GET,
            uri: sprintf('/children/%s', $child->getId()),
            parameters: ['v' => 100]
        );

        /** @var array<string, mixed> $responseContent */
        $responseContent = json_decode(
            json: $this->client->getResponse()->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertResponseStatusCodeSame(200);

        // I should get the following data
        $expectedKeys = [
            'id',
            'numberOfGoodActions',
            'numberOfBadActions',
        ];

        foreach ($expectedKeys as $expectedKey) {
            self::assertArrayHasKey(
                $expectedKey,
                $responseContent,
            );
        }
    }

    public function testGetChildFailsBecauseChildDoesNotExist(): void
    {
        //  When I get a child with a random id
        $this->client->request(
            method: Request::METHOD_GET,
            uri: sprintf('/children/%s', new Ulid()),
            parameters: ['v' => 100]
        );

        self::assertResponseStatusCodeSame(404);
    }
}
