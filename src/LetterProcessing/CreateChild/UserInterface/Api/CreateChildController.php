<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\UserInterface\Api;

use App\LetterProcessing\CreateChild\Application\Command\CreateChild;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Api\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class CreateChildController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JsonResponder $jsonResponder,
    ) {
    }

    #[Route(path: '/children', methods: [Request::METHOD_POST])]
    public function __invoke(CreateChildDto $dto): JsonResponse
    {
        $this->commandBus->command(new CreateChild(
            (string) new Ulid(),
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            streetNumber: $dto->streetNumber,
            street: $dto->street,
            city: $dto->city,
            zipCode: $dto->zipCode,
            isoCountryCode: $dto->isoCountryCode,
        ));

        return $this->jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
