<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\Query;

use App\ChildWatching\GetChild\Application\ReadModel\GetChildReadModelDto;
use App\ChildWatching\Shared\Domain\ActionRepositoryInterface;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\Shared\Application\AbstractQueryHandler;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetChildHandler extends AbstractQueryHandler
{
    public function __construct(
        private readonly ChildRepositoryInterface $childRepository,
        private readonly ActionRepositoryInterface $actionRepository,
        ContainerInterface $container,
    ) {
        parent::__construct($container);
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetChild $query): object
    {
        $child = $this->childRepository->get($query->childId);
        $actions = $this->actionRepository->getActionsOfChildThisYear($child);

        $readModelDto = new GetChildReadModelDto($child, $actions);

        return $this->getResult($query->apiVersion, $readModelDto);
    }
}
