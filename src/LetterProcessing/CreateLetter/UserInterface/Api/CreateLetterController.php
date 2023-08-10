<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\UserInterface\Api;

use App\LetterProcessing\CreateLetter\Application\Command\CreateLetter;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class CreateLetterController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JsonResponder $jsonResponder,
    ) {
    }

    #[Route(path: '/children/{id}/letters', requirements: ['id' => Requirement::ULID], methods: [Request::METHOD_POST])]
    public function __invoke(
        string $id,
        CreateLetterDto $createLetterDto,
    ): JsonResponse {
        $this->commandBus->command(new CreateLetter(
            (string) new Ulid(),
            childId: $id,
            receivingDate: $createLetterDto->receivingDate,
            senderStreetNumber: $createLetterDto->senderStreetNumber,
            senderStreet: $createLetterDto->senderStreet,
            senderCity: $createLetterDto->senderCity,
            senderZipCode: $createLetterDto->senderZipCode,
            senderIsoCountryCode: $createLetterDto->senderIsoCountryCode,
        ));

        return $this->jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
