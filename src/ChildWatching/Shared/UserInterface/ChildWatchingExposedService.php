<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\UserInterface;

use App\ChildWatching\GetChild\UserInterface\Api\GetChildController;
use App\Shared\Application\ApiVersion;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Ulid;

final readonly class ChildWatchingExposedService
{
    public function __construct(
        private GetChildController $getChildController,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function getChild(Ulid $childId, ApiVersion $apiVersion): string
    {
        try {
            $jsonResponse = ($this->getChildController)($childId, $apiVersion);
        } catch (\Throwable $exception) {
            if ($exception instanceof HandlerFailedException) {
                /** @var \Throwable $exception */
                $exception = $exception->getPrevious();
            }

            throw $exception;
        }

        return (string) $jsonResponse->getContent();
    }
}
