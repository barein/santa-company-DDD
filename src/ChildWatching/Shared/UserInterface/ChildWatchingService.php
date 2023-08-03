<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\UserInterface;

use App\ChildWatching\GetChild\Application\Query\GetChild;
use App\Shared\Application\ApiVersion;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

final class ChildWatchingService
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getChild(Ulid $childId, ApiVersion $apiVersion): string
    {
        $readModel = $this->queryBus->query(new GetChild($childId, $apiVersion));

        return $this->serializer->serialize($readModel, 'json');
    }
}
