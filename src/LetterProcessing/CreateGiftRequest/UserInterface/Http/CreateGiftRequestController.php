<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\UserInterface\Http;

use App\LetterProcessing\CreateGiftRequest\Application\Command\CreateGiftRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class CreateGiftRequestController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JsonResponder $jsonResponder,
    ) {
    }

    #[Route(path: '/children/{childUlid}/letters/{letterUlid}/gift-requests', requirements: ['childUlid' => Requirement::ULID, 'letterUlid' => Requirement::ULID], methods: ['POST'])]
    public function __invoke(
        string $childUlid,
        string $letterUlid,
        CreateGiftRequestDto $dto,
    ): JsonResponse {
        $this->commandBus->command(new CreateGiftRequest($childUlid, $letterUlid, $dto->giftName));

        return $this->jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
