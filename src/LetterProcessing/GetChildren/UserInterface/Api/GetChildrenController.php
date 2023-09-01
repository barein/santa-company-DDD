<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\UserInterface\Api;

use App\LetterProcessing\GetChildren\Application\Query\GetChildren;
use App\LetterProcessing\GetChildren\Application\Query\GetChildrenResult;
use App\LetterProcessing\GetChildren\UserInterface\Api\ReadModel\GetChildrenResultToChildReadModelInterfacesMapper;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\Infrastructure\Bus\QueryBus;
use App\Shared\UserInterface\Api\JsonResponder;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetChildrenController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetChildrenResultToChildReadModelInterfacesMapper $readModelInterfacesMapper,
        private readonly JsonResponder $jsonResponder,
    ) {
    }

    #[Route(path: '/children', methods: Request::METHOD_GET)]
    public function __invoke(ApiVersion $apiVersion): JsonResponse
    {
        /** @var GetChildrenResult $getChildrenResult */
        $getChildrenResult = $this->queryBus->query(new GetChildren());

        $readModels = $this->readModelInterfacesMapper->map($getChildrenResult, $apiVersion);

        return $this->jsonResponder->response(HttpStatusCode::OK, $readModels);
    }
}
