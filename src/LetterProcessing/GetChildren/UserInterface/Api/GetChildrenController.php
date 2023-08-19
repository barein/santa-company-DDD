<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\UserInterface\Api;

use App\LetterProcessing\GetChildren\Application\Query\GetChildren;
use App\LetterProcessing\GetChildren\Application\ReadModel\V1\ChildrenReadModel;
use App\Shared\Application\ApiVersion;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\Infrastructure\Bus\QueryBus;
use App\Shared\UserInterface\Api\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetChildrenController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly JsonResponder $jsonResponder,
    ) {
    }

    #[Route(path: '/children', methods: Request::METHOD_GET)]
    public function __invoke(ApiVersion $apiVersion): JsonResponse
    {
        /** @var ChildrenReadModel $childrenReadModel */
        $childrenReadModel = $this->queryBus->query(new GetChildren($apiVersion));

        return $this->jsonResponder->response(HttpStatusCode::OK, $childrenReadModel->children);
    }
}
