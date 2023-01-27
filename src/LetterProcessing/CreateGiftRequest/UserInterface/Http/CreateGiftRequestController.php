<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\UserInterface\Http;

use App\LetterProcessing\CreateGiftRequest\Application\CreateGiftRequest;
use App\Shared\Domain\HttpStatusCode;
use App\Shared\Infrastructure\Bus\SyncCommandBus;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class CreateGiftRequestController extends AbstractController
{
    #[Route(path: '/children/{childUlid}/letters/{letterUlid}/gift-requests', requirements: ['childUlid' => Requirement::ULID, 'letterUlid' => Requirement::ULID], methods: ['POST'])]
    public function __invoke(
        string $childUlid,
        string $letterUlid,
        SyncCommandBus $commandBus,
        JsonResponder $jsonResponder,
        CreateGiftRequestDto $dto,
    ): JsonResponse {
        $commandBus->command(new CreateGiftRequest($childUlid, $letterUlid, $dto->giftName));

        return $jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
