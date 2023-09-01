<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Api;

use App\ChildWatching\GetChild\Application\Query\GetChild;
use App\ChildWatching\GetChild\Application\Query\GetChildResult;
use App\ChildWatching\GetChild\UserInterface\Api\ReadModel\GetChildResultToChildReadModelInterfaceMapper;
use App\Shared\Application\Bus\QueryBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Api\JsonResponder;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class GetChildController extends AbstractController
{
    public function __construct(
        private readonly JsonResponder $jsonResponder,
        private readonly QueryBusInterface $queryBus,
        private readonly GetChildResultToChildReadModelInterfaceMapper $mapper,
    ) {
    }

    #[Route(
        path: '/children/{id}',
        requirements: ['id' => Requirement::ULID],
        methods: Request::METHOD_GET
    )]
    public function __invoke(Ulid $id, ApiVersion $apiVersion): JsonResponse
    {
        /** @var GetChildResult $getChildResult */
        $getChildResult = $this->queryBus->query(new GetChild($id));

        $childReadModel = $this->mapper->map($getChildResult, $apiVersion);

        return $this->jsonResponder->response(HttpStatusCode::OK, $childReadModel);
    }
}
