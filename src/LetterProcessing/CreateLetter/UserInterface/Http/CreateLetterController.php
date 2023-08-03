<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\UserInterface\Http;

use App\LetterProcessing\CreateLetter\Application\Command\CreateLetter;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class CreateLetterController extends AbstractController
{
    #[Route(path: '/children/{id}/letters', requirements: ['id' => Requirement::ULID], methods: ['POST'])]
    public function __invoke(
        string $id,
        CreateLetterDto $createLetterDto,
        CommandBusInterface $commandBus,
        JsonResponder $jsonResponder,
    ): JsonResponse {
        $commandBus->command(new CreateLetter(
            childId: (string) $id,
            receivingDate: $createLetterDto->receivingDate,
            senderStreetNumber: $createLetterDto->senderStreetNumber,
            senderStreet: $createLetterDto->senderStreet,
            senderCity: $createLetterDto->senderCity,
            senderZipCode: $createLetterDto->senderZipCode,
            senderIsoCountryCode: $createLetterDto->senderIsoCountryCode,
        ));

        return $jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
