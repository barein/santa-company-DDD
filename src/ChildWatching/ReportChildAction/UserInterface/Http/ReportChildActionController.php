<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\UserInterface\Http;

use App\ChildWatching\ReportChildAction\Application\Command\ReportChildAction;
use App\Shared\Domain\Bus\SyncCommandBusInterface;
use App\Shared\Domain\HttpStatusCode;
use App\Shared\UserInterface\Http\JsonResponder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Ulid;

class ReportChildActionController extends AbstractController
{
    #[Route(path: '/children/{ulid}/actions', requirements: ['ulid' => Requirement::ULID], methods: ['POST'])]
    public function __invoke(
        Ulid $childUlid,
        ReportChildActionDto $reportChildActionDto,
        JsonResponder $jsonResponder,
        SyncCommandBusInterface $commandBus,
    ): Response {
        $commandBus->command(new ReportChildAction(
            (string) $childUlid,
            $reportChildActionDto->dateTime,
            $reportChildActionDto->description,
            $reportChildActionDto->type,
        ));

        return $jsonResponder->response(HttpStatusCode::HTTP_CREATED);
    }
}
