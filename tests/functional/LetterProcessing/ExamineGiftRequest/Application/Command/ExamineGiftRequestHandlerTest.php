<?php

declare(strict_types=1);

namespace Tests\functional\LetterProcessing\ExamineGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildRequestedAGift;
use App\LetterProcessing\Shared\Domain\ChildWatchingGatewayInterface;
use App\LetterProcessing\Shared\Domain\GiftRequestStatus;
use App\LetterProcessing\Shared\Domain\SantaList;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Domain\Exception\ExternalDependencyFailedException;
use Fixtures\Factory\LetterProcessing\ChildFactory;
use Fixtures\Factory\LetterProcessing\GiftRequestFactory;
use Fixtures\Factory\LetterProcessing\LetterFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\functional\AbstractFunctionalTestCase;

class ExamineGiftRequestHandlerTest extends AbstractFunctionalTestCase
{
    private EventBusInterface $eventBus;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventBus = static::getContainer()->get(EventBusInterface::class);
        $application = new Application(static::$kernel);
        $command = $application->find('messenger:consume');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @dataProvider successFullCasesProvider
     */
    public function testExamineGiftRequestSuccessfully(SantaList $santaList, GiftRequestStatus $giftRequestStatus): void
    {
        // Given a child requested a gift in its letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $giftRequest = GiftRequestFactory::createOne(['letter' => $letter]);
        $this->emptyEventsToDispatchList();

        // And given the Child Watching context will say this child is on the good/bad list
        $childWatchingGateway = $this->createStub(ChildWatchingGatewayInterface::class);
        $childWatchingGateway
            ->method('getSantaListForChild')
            ->willReturn($santaList);
        static::getContainer()->set(ChildWatchingGatewayInterface::class, $childWatchingGateway);

        $this->letterProcessingContextQueueShouldBeEmpty();

        // When the event notifying that the child requested a gift is dispatched
        $this->eventBus->dispatch(new ChildRequestedAGift(
            childId: (string) $child->getId(),
            letterId: (string) $letter->getId(),
            giftRequestId: (string) $giftRequest->getId(),
        ));

        $this->letterProcessingContextQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['letter_processing_queue'],
            '--limit' => 1,
        ]);

        // Then, the event should be consumed successfully
        $this->commandTester->assertCommandIsSuccessful();

        $this->letterProcessingContextQueueShouldBeEmpty();

        // And the gift request should be in the expected status
        self::assertEquals($giftRequestStatus, $giftRequest->getStatus());
    }

    public function successFullCasesProvider(): iterable
    {
        yield 'Child is on santa good list so gift request is granted' => [SantaList::GOOD, GiftRequestStatus::GRANTED];
        yield 'Child is on santa bad list so gift request is declined' => [SantaList::BAD, GiftRequestStatus::DECLINED];
    }

    public function testExamineGiftRequestFailsIfCallToChildWatchingContextFails(): void
    {
        // Given a child requested a gift in its letter
        $child = ChildFactory::createOne();
        $letter = LetterFactory::createOne(['child' => $child]);
        $giftRequest = GiftRequestFactory::createOne(['letter' => $letter]);
        $this->emptyEventsToDispatchList();

        // And given the Child Watching context experiment a 404 not found error
        $childWatchingGateway = $this->createStub(ChildWatchingGatewayInterface::class);
        $childWatchingGateway
            ->method('getSantaListForChild')
            ->willThrowException(new ExternalDependencyFailedException(404));
        static::getContainer()->set(ChildWatchingGatewayInterface::class, $childWatchingGateway);

        $this->letterProcessingContextQueueShouldBeEmpty();

        // When the event notifying that the child requested a gift is dispatched
        $this->eventBus->dispatch(new ChildRequestedAGift(
            childId: (string) $child->getId(),
            letterId: (string) $letter->getId(),
            giftRequestId: (string) $giftRequest->getId(),
        ));

        $this->letterProcessingContextQueueShouldContainThisNumberOfMessage(1);

        // And this event is consumed
        $this->commandTester->execute([
            'receivers' => ['letter_processing_queue'],
            '--limit' => 1,
        ]);

        // Then the gift request status should not have changed
        self::assertEquals(GiftRequestStatus::PENDING, $giftRequest->getStatus());
    }
}
