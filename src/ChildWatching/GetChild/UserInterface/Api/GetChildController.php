<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Api;

use App\ChildWatching\GetChild\Application\Query\GetChild;
use App\Shared\Application\ApiVersion;
use App\Shared\Application\Bus\QueryBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class GetChildController extends AbstractController
{
    public function __construct(
        private readonly JsonResponder $jsonResponder,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route(path: '/children/{id}', requirements: ['id' => Requirement::ULID], methods: ['GET'])]
    public function __invoke(
        Ulid $id,
        ApiVersion $apiVersion,
    ): JsonResponse {
        $readModel = $this->queryBus->query(new GetChild($id, $apiVersion));

        return $this->jsonResponder->response(HttpStatusCode::OK, $readModel);
    }
}
