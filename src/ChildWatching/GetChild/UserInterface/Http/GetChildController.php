<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Http;

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
    #[Route(path: '/children/{ulid}', requirements: ['ulid' => Requirement::ULID], methods: ['GET'])]
    public function __invoke(
        Ulid $childUlid,
        ApiVersion $apiVersion,
        JsonResponder $jsonResponder,
        QueryBusInterface $queryBus,
    ): JsonResponse {
        $readModel = $queryBus->query(new GetChild($childUlid, $apiVersion));

        return $jsonResponder->response(HttpStatusCode::OK, $readModel);
    }
}
