<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\UserInterface\Api;

use App\ChildWatching\ReportChildAction\Application\Command\ReportChildAction;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Domain\Exception\HttpStatusCode;
use App\Shared\UserInterface\Api\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class ReportChildActionController extends AbstractController
{
    public function __construct(
        private readonly JsonResponder $jsonResponder,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    #[Route(path: '/children/{id}/actions', requirements: ['id' => Requirement::ULID], methods: [Request::METHOD_POST])]
    public function __invoke(
        string $id,
        ReportChildActionDto $reportChildActionDto,
    ): Response {
        $this->commandBus->command(new ReportChildAction(
            (string) new Ulid(),
            $id,
            $reportChildActionDto->dateTime,
            $reportChildActionDto->description,
            $reportChildActionDto->type,
        ));

        return $this->jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
