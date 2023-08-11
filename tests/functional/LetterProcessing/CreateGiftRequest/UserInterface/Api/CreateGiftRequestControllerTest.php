<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\CreateGiftRequest\UserInterface\Api;

use App\LetterProcessing\Shared\Domain\GiftRequest\ChildRequestedAGift;
use App\LetterProcessing\Shared\Domain\GiftRequest\GiftRequest;
use App\LetterProcessing\Shared\Domain\GiftRequest\GiftRequestStatus;
use App\LetterProcessing\Shared\Domain\Letter\Letter;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Fixtures\Factory\LetterProcessing\GiftRequestFactory;
use Fixtures\Factory\LetterProcessing\LetterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;
use Tests\functional\AbstractFunctionalTestCase;

class CreateGiftRequestControllerTest extends AbstractFunctionalTestCase
{
    private EntityPersister $giftRequestPersister;

    protected function setUp(): void
    {
        parent::setUp();
        $this->giftRequestPersister = $this->getEntityPersisterForClass(GiftRequest::class);
    }

    public function testCreateGiftRequestSuccessfully(): void
    {
        // Given that a child exist, and he sent a letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $this->emptyEventsToDispatchList();

        // And gifts requested in the letter have not been created yet
        $this->assertNoGiftRequestIsLinkedToLetter($letter->object());

        $this->givenAnInitialNumberOfStoredEvent();
        $this->letterProcessingContextQueueShouldBeEmpty();

        // When I create a gift request for the letter of this child
        $requestContent = [
            'giftName' => 'Teddy bear',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', $child->getId(), $letter->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 201
        self::assertResponseStatusCodeSame(201);

        // And a gift request should be linked to this letter
        $this->assertNumberOfGiftRequestLinkedToLetterEquals($letter->object(), 1);

        $this->numberOfEventStoredShouldHaveIncreasedBy(1);

        // And last event stored should be marked as dispatched
        $lastStoredEvent = $this->getLastStoredEvent();
        self::assertEquals(ChildRequestedAGift::getName(), $lastStoredEvent->getName());
        self::assertTrue($lastStoredEvent->hasBeenDispatched());

        $this->letterProcessingContextQueueShouldContainThisNumberOfMessage(1);
    }

    public function testCreateGiftRequestFailsBecauseChildDoesNotExist(): void
    {
        // Given that a child exist, and he sent a letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $this->emptyEventsToDispatchList();

        $this->givenAnInitialNumberOfStoredEvent();

        // When I create a gift request, using a random child id
        $requestContent = [
            'giftName' => 'Teddy bear',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', new Ulid(), $letter->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 404
        self::assertResponseStatusCodeSame(404);

        $this->assertNoGiftRequestIsLinkedToLetter($letter->object());

        $this->numberOfEventStoredShouldNotHaveChanged();
        $this->letterProcessingContextQueueShouldBeEmpty();
    }

    public function testCreateGiftRequestFailsBecauseLetterDoesNotExist(): void
    {
        // Given that a child exist, and he sent a letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $this->emptyEventsToDispatchList();

        $this->givenAnInitialNumberOfStoredEvent();

        // When I create a gift request, using a random letter id
        $requestContent = [
            'giftName' => 'Teddy bear',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', $child->getId(), new Ulid()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 404
        self::assertResponseStatusCodeSame(404);

        $this->assertNoGiftRequestIsLinkedToLetter($letter->object());

        $this->numberOfEventStoredShouldNotHaveChanged();
        $this->letterProcessingContextQueueShouldBeEmpty();
    }

    public function testCreateGiftRequestFailsBecauseMaximumNumberOfGiftRequestPerLetterIsReached(): void
    {
        // Given that a child exist, and he sent a letter requesting the maximum number of gifts, and they have been created
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        GiftRequestFactory::createMany(Letter::MAX_NUMBER_OF_GIFT_REQUEST_PER_LETTER, [
            'letter' => $letter,
            'status' => GiftRequestStatus::GRANTED,
        ]);
        $this->emptyEventsToDispatchList();

        $this->givenAnInitialNumberOfStoredEvent();

        // When I create a gift request for the letter of this child
        $requestContent = [
            'giftName' => 'Teddy bear',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', $child->getId(), $letter->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 500
        self::assertResponseStatusCodeSame(500);

        // And the error message should mention that the letter already contains the maximum number of gift request
        self::assertStringContainsString(
            'already contains the maximum number of GiftRequest',
            $this->client->getResponse()->getContent()
        );

        // And the number of gift requests linked to this letter should not have changed
        $this->assertNumberOfGiftRequestLinkedToLetterEquals(
            $letter->object(),
            Letter::MAX_NUMBER_OF_GIFT_REQUEST_PER_LETTER
        );

        $this->numberOfEventStoredShouldNotHaveChanged();
        $this->letterProcessingContextQueueShouldBeEmpty();
    }

    public function testCreateGiftRequestFailsBecauseGiftIsAlreadyRequestedInLetter(): void
    {
        // Given that a child exist, and he sent a letter requesting a gift, which has been created
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $giftRequest = GiftRequestFactory::createOne([
            'letter' => $letter,
            'giftName' => 'Power Rangers',
            'status' => GiftRequestStatus::GRANTED,
        ]);
        $this->emptyEventsToDispatchList();

        $this->givenAnInitialNumberOfStoredEvent();

        // When I create a gift request for the letter of this child with the same gift name
        $requestContent = [
            'giftName' => $giftRequest->getGiftName(),
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', $child->getId(), $letter->getId()),
            content: json_encode($requestContent, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 500
        self::assertResponseStatusCodeSame(500);

        // And the error message should mention that the gift was already requested in the letter
        self::assertMatchesRegularExpression('#Gift .+? was already requested in letter .+?#', $this->client->getResponse()->getContent());

        // And the number of gift requests linked to this letter should not have changed
        $this->assertNumberOfGiftRequestLinkedToLetterEquals($letter->object(), 1);

        $this->numberOfEventStoredShouldNotHaveChanged();
        $this->letterProcessingContextQueueShouldBeEmpty();
    }

    public function testCreateGiftRequestFailsBecauseOfUnprocessablePayload(): void
    {
        // Given that a child exist, and he sent a letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);

        // When I create a gift request for the letter of this child with an empty gift name
        $payload = [
            'giftName' => '',
        ];

        $this->client->request(
            method: Request::METHOD_POST,
            uri: sprintf('/children/%s/letters/%s/gift-requests', $child->getId(), $letter->getId()),
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        // I should get a response with status code 422
        self::assertResponseStatusCodeSame(422);
    }

    private function assertNumberOfGiftRequestLinkedToLetterEquals(Letter $letter, int $numberOfGiftRequest): void
    {
        self::assertCount($numberOfGiftRequest, $this->giftRequestPersister->loadAll(['letter' => $letter]));
    }

    private function assertNoGiftRequestIsLinkedToLetter(Letter $letter): void
    {
        $this->assertNumberOfGiftRequestLinkedToLetterEquals($letter, 0);
    }
}
