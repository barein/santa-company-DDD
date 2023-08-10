<?php

declare(strict_types=1);

namespace Tests\functional\ChildWatching\ReportChildAction\UserInterface\Api;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\Child;
use Fixtures\Factory\ChildWatching\ActionFactory;
use Fixtures\Factory\ChildWatching\ChildFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractFunctionalTestCase;

class ReportChildActionControllerTest extends AbstractFunctionalTestCase
{
    public function testReportChildActionSuccessfully(): void
    {
        // Given a child exist and has no action previously reported
        $child = ChildFactory::createOne();
        $this->assertNoActionIsLinkedToChild($child->object());

        // When I report an action of this child
        $payload = [
            'dateTime' => '2023-03-01 15:31:00',
            'description' => 'Help his mother to empty the dishwasher',
            'type' => 'GOOD',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/actions', $child->getId()),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        $this->assertNumberOfActionsLinkedToChildEquals($child->object(), 1);
    }

    public function testReportChildActionFailsBecauseChildDoesNotExist(): void
    {
        // When I report an action of a non-existing child
        $payload = [
            'dateTime' => '2023-03-01 15:31:00',
            'description' => 'Help his mother to empty the dishwasher',
            'type' => 'GOOD',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/actions', (string) new Ulid()),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @dataProvider unprocessablePayloadProvider
     */
    public function testReportChildActionFailsBecauseOfUnprocessablePayload(array $payload): void
    {
        // Given a child exist
        $child = ChildFactory::createOne();

        // When I report an action of this child with an unprocessable payload
        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/actions', $child->getId()),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(422);

        $this->assertNoActionIsLinkedToChild($child->object());
    }

    public function unprocessablePayloadProvider(): iterable
    {
        yield 'Invalid dateTime format' => [[
            'dateTime' => '2023/03/01 15:31:00',
            'description' => 'Help his mother to empty the dishwasher',
            'type' => 'GOOD',
        ]];

        yield 'Empty description' => [[
            'dateTime' => '2023-03-01 15:31:00',
            'description' => '',
            'type' => 'GOOD',
        ]];

        yield 'Invalid action type' => [[
            'dateTime' => '2023-03-01 15:31:00',
            'description' => '',
            'type' => 'NOT SURE',
        ]];
    }

    private function assertNumberOfActionsLinkedToChildEquals(Child $child, int $numberOfActions): void
    {
        self::assertCount($numberOfActions, ActionFactory::repository()->findBy(['childId' => $child->getId()]));
    }

    private function assertNoActionIsLinkedToChild(Child $child): void
    {
        $this->assertNumberOfActionsLinkedToChildEquals($child, 0);
    }
}
