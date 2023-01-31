<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface EventStoreInterface
{
    public function append(DomainEvent $domainEvent): void;
}
