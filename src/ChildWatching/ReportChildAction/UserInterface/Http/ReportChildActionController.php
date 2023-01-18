<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\UserInterface\Http;

use App\ChildWatching\ReportChildAction\UserInterface\Http\Dto\ChildActionDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportChildActionController extends AbstractController
{
    #[Route(path: '/test', methods: ['POST'])]
    public function __invoke(
        ChildActionDto $childActionDto,
    ): Response {
        dd($childActionDto);

        return new Response();
    }
}
