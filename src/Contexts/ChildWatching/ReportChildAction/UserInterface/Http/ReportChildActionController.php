<?php

declare(strict_types=1);

namespace App\Contexts\ChildWatching\ReportChildAction\UserInterface\Http;

use App\Contexts\ChildWatching\Shared\Domain\Child;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportChildActionController extends AbstractController
{
    #[Route(path: '/test', methods: ['POST'])]
    public function __invoke(
        EntityManagerInterface $entityManager,
    ): Response {
        $child = new Child();
        $entityManager->persist($child);
        $entityManager->flush();

        return new Response();
    }
}
