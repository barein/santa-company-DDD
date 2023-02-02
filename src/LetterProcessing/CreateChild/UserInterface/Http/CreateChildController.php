<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\UserInterface\Http;

use App\LetterProcessing\CreateChild\Application\Command\CreateChild;
use App\Shared\Domain\Bus\SyncCommandBusInterface;
use App\Shared\Domain\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CreateChildController extends AbstractController
{
    #[Route(path: '/children', methods: ['POST'])]
    public function __invoke(
        SyncCommandBusInterface $commandBus,
        JsonResponder $jsonResponder,
        CreateChildDto $dto,
    ): JsonResponse {
        $commandBus->command(new CreateChild(
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            streetNumber: $dto->streetNumber,
            street: $dto->street,
            city: $dto->city,
            zipCode: $dto->zipCode,
            isoCountryCode: $dto->isoCountryCode,
        ));

        return $jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
