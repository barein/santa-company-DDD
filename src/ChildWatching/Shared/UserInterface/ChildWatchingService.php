<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\UserInterface;

use App\ChildWatching\GetChild\Application\Query\GetChild;
use App\Shared\Domain\ApiVersion;
use App\Shared\Infrastructure\Bus\QueryBus;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

final class ChildWatchingService
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getChild(Ulid $childUlid, ApiVersion $apiVersion): string
    {
        $readModel = $this->queryBus->query(new GetChild($childUlid, $apiVersion));

        return $this->serializer->serialize($readModel, 'json');
    }
}
