<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\Query;

use App\LetterProcessing\Shared\Domain\Child\ChildRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetChildrenHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(GetChildren $query): GetChildrenResult
    {
        $children = $this->childRepository->getAll();

        return new GetChildrenResult($children);
    }
}
