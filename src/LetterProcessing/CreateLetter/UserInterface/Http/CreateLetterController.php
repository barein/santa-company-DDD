<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\UserInterface\Http;

use App\LetterProcessing\CreateLetter\Application\Command\CreateLetter;
use App\Shared\Domain\Bus\SyncCommandBusInterface;
use App\Shared\Domain\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class CreateLetterController extends AbstractController
{
    #[Route(path: '/children/{ulid}/letters', requirements: ['ulid' => Requirement::ULID], methods: ['POST'])]
    public function __invoke(
        Ulid $childUlid,
        CreateLetterDto $createLetterDto,
        SyncCommandBusInterface $commandBus,
        JsonResponder $jsonResponder,
    ): JsonResponse {
        $commandBus->command(new CreateLetter(
            childUlid: (string) $childUlid,
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
