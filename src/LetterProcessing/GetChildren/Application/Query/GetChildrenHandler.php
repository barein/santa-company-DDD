<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\Query;

use App\LetterProcessing\GetChildren\Application\ReadModel\GetChildrenReadModelDto;
use App\LetterProcessing\Shared\Domain\Child\ChildRepositoryInterface;
use App\Shared\Application\AbstractQueryHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetChildrenHandler extends AbstractQueryHandler
{
    public function __construct(
        private readonly ChildRepositoryInterface $childRepository,
        ContainerInterface $container,
    ) {
        parent::__construct($container);
    }

    public function __invoke(GetChildren $query): object
    {
        $children = $this->childRepository->getAll();

        $readModelDto = new GetChildrenReadModelDto($children);

        return $this->getResult($query->version, $readModelDto);
    }
}
